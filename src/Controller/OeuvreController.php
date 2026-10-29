<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Repository\OeuvreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OeuvreController extends AbstractController
{
    #[Route('/oeuvres', name: 'app_oeuvre_index')]
    public function index(OeuvreRepository $repository): Response
    {
        return $this->render('oeuvre/index.html.twig', [
            'oeuvres' => $repository->findAll(),
        ]);
    }

    #[Route('/oeuvres/{id}', name: 'app_oeuvre_show', requirements: ['id' => '\d+'])]
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }
}
