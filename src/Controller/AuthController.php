<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function register(): Response
    {
        return $this->render('auth/register.html.twig', [
            'controller_name' => 'AuthController',
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