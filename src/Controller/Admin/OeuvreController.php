<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use App\Entity\Lieu;
use App\Entity\Oeuvre;
use App\Entity\Photo;
use App\Entity\Technique;
use App\Form\OeuvreType;
use App\Repository\ArtisteRepository;
use App\Repository\OeuvreRepository;
use App\Repository\TechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/oeuvre')]
final class OeuvreController extends AbstractController
{
    #[Route(name: 'app_admin_oeuvre_index', methods: ['GET'])]
    public function index(OeuvreRepository $oeuvreRepository): Response
    {
        return $this->render('admin/oeuvre/index.html.twig', [
            'oeuvres' => $oeuvreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_oeuvre_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ArtisteRepository $artisteRepository,
        TechniqueRepository $techniqueRepository,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/photos')] string $dossier,
    ): Response {
        $oeuvre = new Oeuvre();
        $form = $this->createForm(OeuvreType::class, $oeuvre, ['avec_photo' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lieu : existant ou nouveau
            $nomLieu = trim((string) $form->get('nouveau_lieu_nom')->getData());
            if ($nomLieu !== '') {
                $adresse = trim((string) $form->get('nouveau_lieu_adresse')->getData());
                if ($adresse === '') {
                    $form->get('nouveau_lieu_adresse')->addError(new FormError('L\'adresse est obligatoire pour un nouveau lieu.'));
                } else {
                    $lieu = new Lieu();
                    $lieu->setNom($nomLieu);
                    $lieu->setAdresse($adresse);
                    $lieu->setQuartier(trim((string) $form->get('nouveau_lieu_quartier')->getData()) ?: null);
                    $lieu->setLatitude(trim((string) $form->get('nouveau_lieu_latitude')->getData()) ?: null);
                    $lieu->setLongitude(trim((string) $form->get('nouveau_lieu_longitude')->getData()) ?: null);
                    $entityManager->persist($lieu);
                    $oeuvre->setLieu($lieu);
                }
            }

            if (!$oeuvre->getLieu()) {
                $form->get('lieu')->addError(new FormError('Choisis un lieu existant ou crée-en un nouveau.'));
            }

            if ($form->isValid()) {
                // Nouveaux artistes (réutilise ceux qui existent déjà)
                foreach (array_filter(array_map('trim', explode(',', (string) $form->get('nouveaux_artistes')->getData()))) as $pseudo) {
                    $artiste = $artisteRepository->findOneBy(['pseudonyme' => $pseudo]);
                    if (!$artiste) {
                        $artiste = new Artiste();
                        $artiste->setPseudonyme($pseudo);
                        $entityManager->persist($artiste);
                    }
                    $oeuvre->addArtiste($artiste);
                }

                // Nouvelles techniques
                foreach (array_filter(array_map('trim', explode(',', (string) $form->get('nouvelles_techniques')->getData()))) as $nom) {
                    $technique = $techniqueRepository->findOneBy(['nom' => $nom]);
                    if (!$technique) {
                        $technique = new Technique();
                        $technique->setNom($nom);
                        $entityManager->persist($technique);
                    }
                    $oeuvre->addTechnique($technique);
                }

                $entityManager->persist($oeuvre);

                // Photo rattachée à l'utilisateur connecté
                $fichier = $form->get('fichier_photo')->getData();
                if ($fichier) {
                    $photo = new Photo();
                    $photo->setOeuvre($oeuvre);
                    $photo->setUtilisateur($this->getUser());
                    $photo->setDateprisevue(new \DateTime());
                    $photo->setEtatoeuvre($form->get('etat_photo')->getData());
                    $photo->setNomfichier($this->enregistrer($fichier, $slugger, $dossier));
                    $entityManager->persist($photo);
                }

                $entityManager->flush();

                return $this->redirectToRoute('app_admin_oeuvre_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/oeuvre/new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_oeuvre_show', methods: ['GET'])]
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('admin/oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_oeuvre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$oeuvre->getLieu()) {
                $form->get('lieu')->addError(new FormError('Le lieu est obligatoire.'));
            } else {
                $entityManager->flush();

                return $this->redirectToRoute('app_admin_oeuvre_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/oeuvre/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_oeuvre_delete', methods: ['POST'])]
    public function delete(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($oeuvre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_oeuvre_index', [], Response::HTTP_SEE_OTHER);
    }

    private function enregistrer(UploadedFile $fichier, SluggerInterface $slugger, string $dossier): string
    {
        $base = $slugger->slug(pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME));
        $nom = $base.'-'.uniqid().'.'.$fichier->guessExtension();
        $fichier->move($dossier, $nom);

        return $nom;
    }
}
