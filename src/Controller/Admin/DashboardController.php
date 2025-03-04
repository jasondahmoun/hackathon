<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Chantier;
use App\Entity\Affectation;
use App\Entity\Besoin;
use App\Entity\Employee;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Redirige vers la liste des Chantiers
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ChantierCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hackathon');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Chantiers', 'fas fa-list', Chantier::class);
        yield MenuItem::linkToCrud('Affectations', 'fas fa-tasks', Affectation::class);
        yield MenuItem::linkToCrud('Besoins', 'fas fa-clipboard-list', Besoin::class);
        yield MenuItem::linkToCrud('Employés', 'fas fa-users', Employee::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
    }
}
