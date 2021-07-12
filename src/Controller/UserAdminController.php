<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserAdminController extends EasyAdminController //AbstractController
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function persistUserEntity($user)
    {
        // Avec FOSUserBundle, on faisait comme ça :
        // $this->get('fos_user.user_manager')->updateUser($user, false);
        // mais maintenant on fait ceci
        $this->updatePassword($user);
        $role [] = ["ROLE_MANAGER"];
        $user->setRoles($role);
        
        // on essaye de persister ici le mot de pass
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        parent::persistEntity($user);
    }

    public function updateUserEntity($user)
    {
        // Avec FOSUserBundle, on faisait comme ça :
        //$this->get('fos_user.user_manager')->updateUser($user, false);
        $role [] = ['ROLE_MANAGER'];
        $user->setRoles($role);
        $this->updatePassword($user);
        parent::updateEntity($user);
    }

    public function updatePassword(User $user)
    {
        if (!empty($user->getPlainPassword())) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        }
    }

    /**
     * @Route("/user/admin", name="user_admin")
     */
    public function index(): Response
    {
        return $this->render('user_admin/index.html.twig', [
            'controller_name' => 'UserAdminController',
        ]);
    }
}