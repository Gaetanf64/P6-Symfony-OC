<?php

namespace App\Controller;

use App\Repository\GroupRepository;
use App\Repository\MediaRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/{id}", name="tricks")
     */
    public function index($id, TrickRepository $trickRepository, MediaRepository $mediaRepository, GroupRepository $groupRepository): Response
    {

        $trick = $trickRepository->findOneBy(['id' => $id]);


        $medias = $mediaRepository->findBy(['trick' => $trick]);

        // TEMPORAIRE
        $media = $mediaRepository->findOneBy(['trick' => $trick]);

        // $groupes = $groupRepository->findAll();
        // $groupe = $trickRepository->findBy(['groupe' => $groupes]);

        // foreach ($groupe as $gr) {
        //     $gr = $trickRepository->findBy(['id' => $id]);
        // }

        $groupe = $groupRepository->findOneByTrick($trick);

        dump($groupe);

        return $this->render('tricks/index.html.twig', [
            'trick' => $trick,
            'medias' => $medias,
            'media' => $media,
            'groupe' => $groupe
        ]);
    }
}
