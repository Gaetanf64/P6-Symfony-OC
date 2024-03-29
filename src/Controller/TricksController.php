<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Form\TrickFormType;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TricksController extends AbstractController
{
    /**
     * Page détails d'un trick
     * 
     * @Route("/tricks/{slug}", name="tricks")
     */
    public function index($slug, TrickRepository $trickRepository, Request $request, CommentRepository $commentRepository): Response
    {
        //On cherche le trick avec son slug
        $trick = $trickRepository->findOneBySlug($slug);

        //Si trick n'existe pas, on redirige vers la page d'accueil
        if (!$trick) {
            return $this->redirectToRoute('home');
        }

        //On récupère les commentaires par trick
        $comments = $commentRepository->findByTrick($trick, array('dateCreation' => 'DESC'));

        //PARTIE AJOUT D'UN COMMENTAIRE
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

            //On regénère la page de la trick
            return $this->redirectToRoute('tricks', ['slug' => $slug]);
        }

        return $this->render('tricks/index.html.twig', [
            'trick' => $trick,
            'formComment' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * Page de voir les medias pour le responsive
     * 
     * @Route("/tricks/medias/{slug}", name="medias")
     */
    public function medias($slug, TrickRepository $trickRepository): Response
    {

        $trick = $trickRepository->findOneBySlug($slug);

        if (!$trick) {
            return $this->redirectToRoute('home');
        }

        return $this->render('tricks/medias.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="edit_trick")
     */
    public function edit(Request $request, $slug, TrickRepository $trickRepository, SluggerInterface $slugger): Response
    {
        //On cherche le trick avec son slug
        $trick = $trickRepository->findOneBySlug($slug);

        //ON récupere l'image principale deja présente
        $imageMainPresent = $trick->getimageMain();

        //Page par defaut si user non connecté
        if ($this->getUser() === null) {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }

        //Redirection si trick non trouvé
        if (!$trick) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(TrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //RECUPERE DONNEES DE L'IMAGE PRINCIPALE

            /** @var UploadedFile $upload */
            $upload = $form->get('imageMain')->getData();

            if ($upload === null) {
                //Si aucune image principale choisie, on garde l'image deja présente
                $trick->setimageMain($imageMainPresent);
            } else {
                //Supprime les données de l'ancienne image principale
                $fileSystem = new Filesystem();

                $fileSystem->remove($trick->getimageMain());

                //Upload de l'image principale
                $originalFilename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);

                //Nom du fichier qui apparîtra dans la bdd
                $newFilename = 'img/' . $safeFilename . '-' . uniqid() . '.' . $upload->guessExtension();

                //Place le fichier dans le chemin défini
                try {
                    $upload->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                //Met à jour le fichier dans la bdd
                $trick->setimageMain($newFilename);
            }

            //RECUPERE DONNEES DES IMAGES

            $galerieImg = $form->get('images')->getData();

            //Met à jour le fichier dans la bdd
            foreach ($galerieImg as $image) {

                //Renommer fichier
                $fichier = 'img/' . md5(uniqid()) . '.' . $image->guessExtension();

                //Place le fichier dans le chemin défini
                $image->move(
                    $this->getParameter('upload_directory'),
                    $fichier
                );

                //Envoi dans la db
                $image = new Image();
                $image->setPath($fichier);

                $trick->addImage($image);
            }

            //RECUPERE URL DE LA VIDEO

            $url = $form->get('videos')->getData();

            //Envoi dans la db de la video
            if ($url !== null) {
                $video = new Video();
                $video->setUrl($url);

                //Lien avec autre table
                $trick->addVideo($video);
            }

            //AJOUT DANS LA BDD

            //Met à jour la table trick
            $trick->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            //Lien avec d'autres bases
            $trick->setUser($this->getUser());

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

            //On dirige vers la page de détails d'un trick
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
        //Page par defaut si user non trouvé
        if ($this->getUser() === null) {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }

        $trick = new Trick();

        $form = $this->createForm(TrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //RECUPERE DONNEES DE L'IMAGE PRINCIPALE

            /** @var UploadedFile $upload */
            $upload = $form->get('imageMain')->getData();

            if ($upload === null) {
                $upload = 'fond.jpg';

                //Nom du fichier qui apparîtra dans la bdd
                $newFilename = 'img/fond.jpg';
            } else {
                $originalFilename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);

                //Nom du fichier qui apparîtra dans la bdd
                $newFilename = 'img/' . $safeFilename . '-' . uniqid() . '.' . $upload->guessExtension();

                //Place le fichier dans le chemin défini
                try {
                    $upload->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            //RECUPERE DONNEES DES IMAGES

            $galerieImg = $form->get('images')->getData();

            //Met à jour le fichier dans la bdd
            foreach ($galerieImg as $image) {

                //Renommer fichier
                $fichier = 'img/' . md5(uniqid()) . '.' . $image->guessExtension();

                //Place le fichier dans le chemin défini
                $image->move(
                    $this->getParameter('upload_directory'),
                    $fichier
                );

                //Envoi dans la db
                $image = new Image();
                $image->setPath($fichier);

                $trick->addImage($image);
            }

            //RECUPERE URL DE LA VIDEO

            $url = $form->get('videos')->getData();

            //Envoi dans la db de la video
            if ($url !== null) {
                $video = new Video();
                $video->setUrl($url);

                //Lien avec autre table
                $trick->addVideo($video);
            }

            //AJOUT DANS LA BDD

            //Met à jour la table trick
            $trick->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));
            $trick->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            $trick->setimageMain($newFilename);


            //Lien avec d'autres bases
            $trick->setUser($this->getUser());

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
     * Page de suppresion des images pour l'edit d'une trick
     * 
     * @Route("/editImages/{slug}", name="editImages")
     * 
     */
    public function editImages(Request $request, TrickRepository $trickRepository, $slug): Response
    {
        $trick = $trickRepository->findOneBySlug($slug);

        //Page par defaut si user non connecté
        if ($this->getUser() === null) {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }

        //Redirection si trick non trouvé
        if (!$trick) {
            return $this->redirectToRoute('home');
        }

        return $this->render('tricks/editImages.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * Delete image par utilisateur
     *
     * @Route("/deleteImg/{slug}/image/{id}", name="delete_img")
     *
     * @param Trick $trick
     *
     * @return Response
     */
    public function deleteImg(Request $request, Image $image, ImageRepository $imageRepository, $id, $slug): Response
    {
        //On récupère l'image avec son id
        $image = $imageRepository->findOneById($id);

        $fileSystem = new Filesystem();

        //On supprime le fichiers du système
        $fileSystem->remove($image->getPath());

        //On instancie doctrine
        $manager = $this->getDoctrine()->getManager();

        //On supprime de la db
        $manager->remove($image);
        $manager->flush();

        $this->addFlash('supprimeImg', "L'image a bien été supprimé.");

        return $this->redirectToRoute('editImages', ['slug' => $slug]);
    }

    /**
     * Delete video par utilisateur
     *
     * @Route("/deleteVideo/{slug}/video/{id}", name="delete_video")
     *
     * @param Video $video
     *
     * @return Response
     */
    public function deleteVideo(Request $request, Video $video, VideoRepository $videoRepository, $id, $slug): Response
    {

        $video = $videoRepository->findOneById($id);

        //On instancie doctrine
        $manager = $this->getDoctrine()->getManager();

        //On supprime de la db
        $manager->remove($video);
        $manager->flush();

        $this->addFlash('supprimeVideo', "La vidéo a bien été supprimé.");

        //return $this->redirectToRoute($_SERVER['HTTP_REFERER']);
        return $this->redirectToRoute('editImages', ['slug' => $slug]);
    }


    /**
     * Delete trick par utilisateur
     *
     * @Route("/tricks/delete/{id}", name="delete_trick")
     *
     * @param Trick $trick
     *
     * @return Response
     */
    public function delete(Trick $trick, TrickRepository $trickRepository, $id): Response
    {
        $trick = $trickRepository->findOneById($id);

        $fileSystem = new Filesystem();

        if ($trick->getimageMain() !== "img/fond.jpg") {
            $fileSystem->remove($trick->getimageMain());
        }

        foreach ($trick->getImages() as $image) {
            $fileSystem->remove($image->getPath());
        }

        //On instancie doctrine
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($trick);
        $manager->flush();

        $this->addFlash('supprime', 'La trick a bien été supprimé.');

        return $this->redirectToRoute('profil');
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
