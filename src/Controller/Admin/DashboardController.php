<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Dossier;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/psonfedhead", name="psonfedhead")
     */
    public function index(): Response
    {
        return $this->render('psonfedhead/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="..."> PSON<span class="text-small">fed.</span>');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Dossiers', 'fa fa-folder-open', Dossier::class);
        # yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class); must create crud User before
    }
}
