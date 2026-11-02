<?php

namespace App\Controller\Admin;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/photo')]
final class PhotoController extends AbstractController
{
    #[Route(name: 'app_admin_photo_index', methods: ['GET'])]
    public function index(PhotoRepository $photoRepository): Response
    {
        return $this->render('admin/photo/index.html.twig', [
            'photos' => $photoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_photo_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/photos')] string $dossier,
    ): Response {
        $photo = new Photo();
        $photo->setUtilisateur($this->getUser());
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo->setNomfichier($this->enregistrer($form->get('fichier')->getData(), $slugger, $dossier));
            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/photo/new.html.twig', [
            'photo' => $photo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_photo_show', methods: ['GET'])]
    public function show(Photo $photo): Response
    {
        return $this->render('admin/photo/show.html.twig', [
            'photo' => $photo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_photo_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Photo $photo,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/photos')] string $dossier,
    ): Response {
        $form = $this->createForm(PhotoType::class, $photo, ['fichier_obligatoire' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('fichier')->getData();
            if ($fichier) {
                $photo->setNomfichier($this->enregistrer($fichier, $slugger, $dossier));
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/photo/edit.html.twig', [
            'photo' => $photo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_photo_delete', methods: ['POST'])]
    public function delete(Request $request, Photo $photo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($photo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_photo_index', [], Response::HTTP_SEE_OTHER);
    }

    private function enregistrer(UploadedFile $fichier, SluggerInterface $slugger, string $dossier): string
    {
        $base = $slugger->slug(pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME));
        $nom = $base.'-'.uniqid().'.'.$fichier->guessExtension();
        $fichier->move($dossier, $nom);

        return $nom;
    }
}
