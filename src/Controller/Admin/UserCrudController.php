<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('fullname'),
            TextField::new('username'),
            TextField::new('email'),
            TextField::new('password')->hideOnIndex(),
            TextField::new('plainPassword')
            ->hideOnIndex(),
            ArrayField::new('roles') // on test pour voir si arrayField existe à revoir cette ligne
        ];
    }
}
