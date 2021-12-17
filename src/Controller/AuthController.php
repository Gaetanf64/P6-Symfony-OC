<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        //Create Form
        $form = $this->createForm(RegistrationFormType::class, $user);

        //Recupère les données saisies
        $form->handleRequest($request);

        //vérification si form envoyé et données valides
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            //$confirm_password = $encoder->encodePassword($user, $user->getConfirmPassword());
            $token = uniqid();

            $user->setDateCreation(new \DateTime(date('Y-m-d H:i:s')))
                ->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')))
                ->setPhotoProfil('img/default.png')
                ->setIsActivated(0)
                ->setRoles(array('ROLE_USER'))
                ->setPassword($password)
                ->setToken($token);

            //On instancie doctrine
            $manager = $this->getDoctrine()->getManager();

            //On hydrate
            $manager->persist($user);

            //Envoi dans la base de données
            $manager->flush();
        }

        return $this->render('auth/register.html.twig', [
            'formRegister' => $form->createView(),
        ]);
    }

    /**
     * @Route("/forgotPassword", name="forgotPassword")
     */
    public function forgotPassword(): Response
    {
        return $this->render('auth/forgotPassword.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    /**
     * @Route("/newPassword", name="newPassword")
     */
    public function newPassword(): Response
    {
        return $this->render('auth/newPassword.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }
}
