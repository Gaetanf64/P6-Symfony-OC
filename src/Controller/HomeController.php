<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $trickRepository, MediaRepository $mediaRepository): Response
    {

        $tricks = $trickRepository->findAll();

        foreach ($tricks as $trick) {
            $trick->medias = $mediaRepository->findOneBy(['trick' => $trick]);
        };

        //dump($tricks);

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
