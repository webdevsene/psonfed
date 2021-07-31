<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Dossier;
use App\Entity\Document;
use App\Repository\UserRepository;
use App\Repository\DossierRepository;
use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var DossierRepository
     */
    protected DossierRepository $dossierRepository;

    /**
     * @var DocumentRepository
     */
    protected DocumentRepository $documentRepository;

    public function __construct(
        UserRepository $userRepository,
        DossierRepository $dossierRepository,
        DocumentRepository $documentRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->dossierRepository = $dossierRepository;
        $this->documentRepository = $documentRepository;
    }

    /**
     * @Route("/psonfedhead", name="psonfedhead")
     */
    public function index(): Response
    {
        // on recupere le nombre de compte utilisateurs existant dans la base

        // avant on faisait ceci
        /*$users = $this->getDoctrine()
                      ->getRepository(User::class)
                      ->countAllUser()
        ;*/

        // et maintenant avec sf5 on fat ceci
        $users = $this->userRepository
                      ->countAllUser()
        ;

        // recuperer le nombre de dossiers
        $doss = $this->dossierRepository
                     ->countAllDossier()
        ;

        return $this->render('psonfedhead/dashboard.html.twig', [
            'users' => $users,
            'dossiers' => $doss,
            'documents' => $this->documentRepository->findAll()
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        ->setTitle('<div class="container">
                        <img src="../bootstrap/images/logodemarch.png" style="width: 80px;">
                        <img src="../bootstrap/images/flague.png">
                        <img src="../bootstrap/images/flagsn.png">
                    </div>
                    ')
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
            
            MenuItem::section('Paramètres'),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class)->setPermission('ROLE_ADMIN'),

        ];

        //avant on avait le code ci-dessous

        // yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        // yield MenuItem::section('Classeurs');
        // yield MenuItem::linkToCrud('Dossiers', 'fa fa-folder-open', Dossier::class)->setPermission('ROLE_ADMIN');
        // # yield MenuItem::linkToCrud('UtilisateursCorrup', 'fa fa-user', Utilisateur::class); #must create crud User before
        // yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class)->setPermission('ROLE_ADMIN');
        // yield MenuItem::linkToCrud('Documents Liste', 'fa fa-list', Document::class)->setPermission('ROLE_ADMIN');
    }
}
