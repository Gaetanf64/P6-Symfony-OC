<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\User;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Form\TrickFormType;
use App\Repository\UserRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    /**
     * @Route("/edit/{slug}", name="edit_trick")
     */
    public function edit(Request $request, $slug, TrickRepository $trickRepository): Response
    {
        $trick = $trickRepository->findOneBySlug($slug);

        if (!$trick) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(TrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $trick->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            //On instancie doctrine
            $manager = $this->getDoctrine()->getManager();

            //On hydrate
            $manager->persist($trick);

            //Envoi dans la base de données
            $manager->flush();

            // $this->addFlash('edit', 'Votre trick a été modifié avec succés !');

            return $this->redirectToRoute('tricks', ['slug' => $trick->getSlug()]);
        }

        return $this->render('tricks/edit.html.twig', [
            'formEdit' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/add-trick", name="add_trick")
     */
    public function add(Request $request, SluggerInterface $slugger): Response
    {
        $trick = new Trick();
        $media = new Media();



        //$user = $userRepository->findOneById($this->getUser());

        $form = $this->createForm(TrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $upload */
            $upload = $form->get('media')->getData();

            // //SI upload pas null et different du  actuelle
            // if ($upload !== null && $upload !== $user->getPhotoProfil()) {
            $originalFilename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);

            //Nom du fichier qui apparîtra dans la bdd
            $newFilename = 'img/' . $safeFilename . '-' . uniqid() . '.' . $upload->guessExtension();




            // if ($avatarPresent !== 'default.png') {
            // Place le fichier dans le chemin défini
            try {
                $upload->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            //Met à jour le fichier dans la bdd
            $media->setPath($newFilename);
            $media->setIsMain(0);
            $media->setType(1);
            // }

            $trick->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));
            $trick->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));


            //Lien avec d'autres bases
            $trick->setUser($this->getUser());
            //$media->setTrick($trick->getId());
            $trick->addMedium($media);

            //On passe le titre en slug
            $title = $trick->getTitle();
            $slugify = $this->slugify($title);
            $trick->setSlug($slugify);

            //On instancie doctrine
            $manager = $this->getDoctrine()->getManager();

            //On hydrate
            $manager->persist($trick);

            //Envoi dans la base de données
            $manager->flush();

            return $this->redirectToRoute('tricks', ['slug' => $trick->getSlug()]);
        }

        return $this->render('tricks/add.html.twig', [
            'formAdd' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * Transform (e.g. "Hello World") into a slug (e.g. "hello-world").
     *
     * @param string $string
     *
     * @return string
     */
    public function slugify($string)
    {
        $rule = 'NFD; [:Nonspacing Mark:] Remove; NFC';
        $transliterator = \Transliterator::create($rule);
        $string = $transliterator->transliterate($string);

        return preg_replace(
            '/[^a-z0-9]/',
            '-',
            strtolower(trim(strip_tags($string)))
        );
    }
}
