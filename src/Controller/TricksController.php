<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/{slug}", name="tricks")
     */
    public function index($slug, TrickRepository $trickRepository, Request $request, CommentRepository $commentRepository): Response
    {
        $trick = $trickRepository->findOneBySlug($slug);

        if (!$trick) {
            return $this->redirectToRoute('home');
        }


        $comments = $commentRepository->findByTrick($trick, array('dateCreation' => 'DESC'));



        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            $comment->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            $comment->setTrick($trick);
            $comment->setUser($this->getUser());

            //On instancie doctrine
            $manager = $this->getDoctrine()->getManager();

            //On hydrate
            $manager->persist($comment);

            //Envoi dans la base de données
            $manager->flush();

            $this->addFlash('comment', 'Votre commentaire a bien été enregistré');
        }

        return $this->render('tricks/index.html.twig', [
            'trick' => $trick,
            'formComment' => $form->createView(),
            'comments' => $comments
        ]);
    }
}
