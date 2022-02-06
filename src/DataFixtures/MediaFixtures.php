<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($t = 1; $t < 11; $t++) {
            for ($i = 1; $i < 5; $i++) {
                $image = new Image();

                //Lien avec d'autres fixtures
                $trick = $this->getReference('trick_' . $t);
                $image->setTrick($trick);

                $image->setPath('img/tricks' . $t . '.' . $i . '.jpg');

                $manager->persist($image);
            }
        }

        for ($t = 1; $t < 11; $t++) {
            $video = new Video();

            //Lien avec d'autres fixtures
            $trick = $this->getReference('trick_' . $t);
            $video->setTrick($trick);

            $video->setUrl('https://www.youtube.com/embed/SQyTWk7OxSI');

            $manager->persist($video);
        }

        for ($t = 1; $t < 11; $t++) {
            $video = new Video();

            //Lien avec d'autres fixtures
            $trick = $this->getReference('trick_' . $t);
            $video->setTrick($trick);

            $video->setUrl('https://www.dailymotion.com/embed/video/x2hdas8');

            $manager->persist($video);
        }


        $manager->flush();
    }
}
