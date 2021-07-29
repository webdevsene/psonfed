<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Dossier;
use App\Entity\Document;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

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
        ->setTitle('<img src="../bootstrap/images/logodemarch.png">')
        // set this option if you prefer the sidebar (which contains the main menu)
        // to be displayed as a narrow column instead of the default expanded design
        //->renderSidebarMinimized()
        // set this option if you prefer the page content to span the entire
        // browser width, instead of the default design which sets a max width
        ->renderContentMaximized()
        ;
    }

    public function configureMenuItems(): iterable
    {

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home')->setPermission('ROLE_ADMIN'),

            MenuItem::section('Classeurs'),
            MenuItem::subMenu('Classeur', 'fa fa-list-alt')
            ->setCssClass('')
            ->setSubItems([

                MenuItem::linkToCrud('Dossiers', 'fa fa-folder-open', Dossier::class)->setPermission('ROLE_ADMIN'),
                MenuItem::linkToCrud('Documents', 'fa fa-file-text', Document::class)->setPermission('ROLE_ADMIN'),

            ]),
            
            MenuItem::section('Users'),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class)->setPermission('ROLE_ADMIN'),

        ];

        // yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        // yield MenuItem::section('Classeurs');
        // yield MenuItem::linkToCrud('Dossiers', 'fa fa-folder-open', Dossier::class)->setPermission('ROLE_ADMIN');
        // # yield MenuItem::linkToCrud('UtilisateursCorrup', 'fa fa-user', Utilisateur::class); #must create crud User before
        // yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class)->setPermission('ROLE_ADMIN');
        // yield MenuItem::linkToCrud('Documents Liste', 'fa fa-list', Document::class)->setPermission('ROLE_ADMIN');
    }
}
