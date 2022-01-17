<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\NewPasswordFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
        // retrouver une erreur d'authentification s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // retrouver le dernier identifiant de connexion utilisé
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'auth/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, MailerInterface $mailer): Response
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

            //Envoi du mail de confirmation
            // $email = (new Email())
            //     ->from('gaetan.fouillet@greta-cfa-aquitaine.academy')
            //     ->to('gaetan.fouillet@greta-cfa-aquitaine.academy')
            //     ->subject('Confirmation de votre inscription');
            // // ->text('Sending emails is fun again!')
            // // ->html('<p>Bonjour</p>');

            // $mailer->send($email);
            // $content = $this->renderView('emails/registration.html.twig', [

            // ]);

            // $headers = 'From: "gaetan.fouillet@greta-cfa-aquitaine.academy"';
            // $headers .= 'Content-Type: text/html; charset="iso-8859-1"';
            // mail($user->getEmail(), $subject, $content, $headers);

            $email = (new TemplatedEmail())
                ->from('gaetan.fouillet@greta-cfa-aquitaine.academy')
                ->to($user->getEmail())
                ->subject('Confirmation de votre inscription')

                // path of the Twig template to render
                ->htmlTemplate('mails/register.html.twig')
                ->context([
                    'username' => $user->getUsername(),
                    'id' => $user->getId(),
                    'token' => $user->getToken(),
                    'address' => $request->server->get('SERVER_NAME')
                ]);
            $mailer->send($email);

            $this->addFlash('success', 'Votre compte a été crée avec succès, un email de confirmation vous a été envoyé.');
        }

        return $this->render('auth/register.html.twig', [
            'formRegister' => $form->createView(),
        ]);
    }

    /**
     * Email confirm
     * 
     * @Route("/confirm", name="confirm")
     */
    public function confirm(Request $request, UserRepository $userRepository): Response
    {
        // if (!$request->query->get('id')) {
        //     throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour vous valider !');
        // }
        // if (!$request->query->get('token')) {
        //     throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour vous valider !');
        // }

        $id = $request->query->get('id');
        $token = $request->query->get('token');

        $user = $userRepository->findOneBy(['id' => $id]);
        if ($user->getId() && $user->getToken() === $token) {
            $user->setToken(null)
                ->setIsActivated(1);

            //On instancie doctrine
            $manager = $this->getDoctrine()->getManager();

            //On hydrate
            $manager->persist($user);

            //Envoi dans la base de données
            $manager->flush();

            $this->addFlash('validation', 'Votre compte est validé ! Connectez-vous !');

            return $this->redirectToRoute('account_login');
        }

        //throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour vous valider !');
    }

    /**
     * @Route("/forgotPassword", name="forgotPassword")
     */
    public function forgotPassword(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, MailerInterface $mailer): Response
    {

        $user = new User();

        //$user = $userRepository->findOneByEmail($email);

        //Create Form
        $form = $this->createForm(ForgotPasswordFormType::class, $user);

        //Recupère les données saisies
        $form->handleRequest($request);

        //vérification si form envoyé et données valides
        if ($form->isSubmitted() && $form->isValid()) {

            //$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $user->email]);

            $email = $form->getData('email');

            //dump($email);

            $user = $userRepository->findOneBy(['email' => $email->getEmail()]);


            //dump($user);

            //$user = $userRepository->findOneBy(['email' => $email->getUsername()]);

            $token = uniqid();

            $user->setToken($token);

            //On instancie doctrine
            $manager = $this->getDoctrine()->getManager();

            //On hydrate
            $manager->persist($user);

            //Envoi dans la base de données
            $manager->flush();

            //Envoi du mail de réintialisation
            $email = (new TemplatedEmail())
                ->from('gaetan.fouillet@greta-cfa-aquitaine.academy')
                ->to($user->getEmail())
                ->subject('Réintialisation du mot de passe')

                // path of the Twig template to render
                ->htmlTemplate('mails/forgotPassword.html.twig')
                ->context([
                    'username' => $user->getUsername(),
                    'id' => $user->getId(),
                    'token' => $user->getToken(),
                    'address' => $request->server->get('SERVER_NAME')
                ]);
            $mailer->send($email);

            $this->addFlash('forgotPassword', 'Un email pour réintialiser votre mot de passe vous a été envoyé.');
        }

        return $this->render('auth/forgotPassword.html.twig', [
            'formForgotPassword' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newPassword", name="newPassword")
     */
    public function newPassword(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $token = $request->query->get('token');
        $id = $request->query->get('id');

        $user = $userRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(NewPasswordFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getId() && $user->getToken() === $token) {
                $newPassword = $user->getPassword();
                $password = $encoder->encodePassword($user, $newPassword);
                $user->setToken(null)
                    ->setPassword($password);

                //On instancie doctrine
                $manager = $this->getDoctrine()->getManager();

                //On hydrate
                $manager->persist($user);

                //Envoi dans la base de données
                $manager->flush();

                $this->addFlash('newPassword', 'Votre mot de passe a été mis à jour.');

                //return $this->redirectToRoute('account_login');
            }
        }

        return $this->render('auth/newPassword.html.twig', [
            'formNewPassword' => $form->createView(),
        ]);
    }

    /**
     * Logout function
     *
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(): void
    {
    }
}
