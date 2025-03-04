<?php

namespace App\Controller;

use App\Entity\Chantier;
use App\Repository\ChantierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chantier')]
final class ChantierController extends AbstractController
{
    #[Route(name: 'app_chantier_index', methods: ['GET'])]
    public function index(ChantierRepository $chantierRepository): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté

        if ($this->isGranted('ROLE_ADMIN')) {
            // Si l'utilisateur est un admin, afficher tous les chantiers
            $chantiers = $chantierRepository->findAll();
        } else {
            // Sinon, récupérer les chantiers de l'employé connecté
            $employe = $user->getEmploye();
            $chantiers = $chantierRepository->findByEmploye($employe);
        }

        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantiers,
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
