<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Form\UserType;
use App\Form\Type\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
/**
 * Controller used to manage current user.
 *
 * @Route("/profile")
 * @IsGranted("ROLE_USER")
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class UserController extends EasyAdminController
{

    //use AbstractController;
    /**
     * @var UserPasswordHasherInterface
     */
        public $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function persistUserEntity($user): void
    {
        // Avec FOSUserBundle, on faisait comme ça :
        // $this->get('fos_user.user_manager')->updateUser($user, false);
        $user->setPassword($this->passwordEncoder->hashPassword($user));
        
        $this->updatePassword($user);

        parent::persistEntity($user);
    }


    /**
     * @Route("/edit", methods="GET|POST", name="user_edit")
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/change-password", methods="GET|POST", name="user_change_password")
     */
    public function changePassword(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('newPassword')->getData()));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('security_logout');
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
