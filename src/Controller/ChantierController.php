<?php

namespace App\Controller;

use App\Entity\Chantier;
use App\Form\ChantierType;
use App\Repository\ChantierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chantier')]
final class ChantierController extends AbstractController
{
    #[Route(name: 'app_chantier_index', methods: ['GET'])]
    public function index(ChantierRepository $chantierRepository): Response
    {
        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantierRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_chantier_show', methods: ['GET'])]
    public function show(Chantier $chantier): Response
    {
        return $this->render('chantier/show.html.twig', [
            'chantier' => $chantier,
        ]);
    }
}
