<?php

namespace App\DataFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $medias = [
            1 => [
                'type' => 1,
                'path' => 'img/tricks1.jpg',
                'is_main' => 1,
                'trick' => 1
            ],
            2 => [
                'type' => 1,
                'path' => 'img/tricks2.jpg',
                'is_main' => 1,
                'trick' => 2
            ],
            3 => [
                'type' => 1,
                'path' => 'img/tricks3.jpg',
                'is_main' => 1,
                'trick' => 3
            ],
            4 => [
                'type' => 1,
                'path' => 'img/tricks4.jpg',
                'is_main' => 1,
                'trick' => 4
            ],
            5 => [
                'type' => 1,
                'path' => 'img/tricks5.jpg',
                'is_main' => 1,
                'trick' => 5
            ],
            6 => [
                'type' => 1,
                'path' => 'img/tricks6.jpg',
                'is_main' => 1,
                'trick' => 6
            ],
            7 => [
                'type' => 1,
                'path' => 'img/tricks7.jpg',
                'is_main' => 1,
                'trick' => 7
            ],
            8 => [
                'type' => 1,
                'path' => 'img/tricks8.jpg',
                'is_main' => 1,
                'trick' => 8
            ],
            9 => [
                'type' => 1,
                'path' => 'img/tricks9.jpg',
                'is_main' => 1,
                'trick' => 9
            ],
            10 => [
                'type' => 1,
                'path' => 'img/tricks10.jpg',
                'is_main' => 1,
                'trick' => 10
            ],

        ];

        for ($t = 1; $t < 11; $t++) {
            for ($i = 1; $i < 5; $i++) {
                $image = new Media();
                $image->setType(1);
                $image->setIsMain(0);

                //Lien avec d'autres fixtures
                $trick = $this->getReference('trick_' . $t);
                $image->setTrick($trick);

                $image->setPath('img/tricks' . $t . '.' . $i . '.jpg');

                $manager->persist($image);
            }
        }

        for ($t = 1; $t < 11; $t++) {
            $video = new Media();
            $video->setType(0);
            $video->setIsMain(0);

            //Lien avec d'autres fixtures
            $trick = $this->getReference('trick_' . $t);
            $video->setTrick($trick);

            $video->setPath('https://www.youtube.com/embed/SQyTWk7OxSI');

            $manager->persist($video);
        }

        for ($t = 1; $t < 11; $t++) {
            $video = new Media();
            $video->setType(0);
            $video->setIsMain(0);

            //Lien avec d'autres fixtures
            $trick = $this->getReference('trick_' . $t);
            $video->setTrick($trick);

            $video->setPath('https://www.dailymotion.com/embed/video/x2hdas8');

            $manager->persist($video);
        }


        foreach ($medias as $key => $value) {
            $media = new Media();
            $media->setType($value['type']);
            $media->setPath($value['path']);
            $media->setIsMain($value['is_main']);
            // $media->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            // $media->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            //Lien avec d'autres fixtures
            $media->setTrick($this->getReference('trick_' . $value['trick']));

            $manager->persist($media);

            // //Add Reference
            // $this->addReference('media' . $key, $media);
        }

        $manager->flush();
    }
}
