<?php

namespace App\Controller\Admin;

use App\Entity\Dossier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DossierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dossier::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('cote'),
            TextField::new('titre'),
            TextField::new('analyse'),
            DateField::new('date_debut'),
            DateField::new('date_butoire'),
            DateField::new('createdAt')
            ->hideOnIndex()
            ->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        /**
         * 
         * affichage en mode tri descendant
         */
        return $crud
            ->setDefaultSort(['date_butoire' => 'DESC'])
            // ...
        ;
    }

}
