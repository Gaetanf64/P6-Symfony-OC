<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tricks = [
            1 => [
                'title' => 'Mute',
                'description' => 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.',
                'user' => 3,
                'groupe' => 1
            ],
            2 => [
                'title' => 'Melancholie',
                'description' => 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.',
                'user' => 3,
                'groupe' => 1
            ],
            3 => [
                'title' => 'Indy',
                'description' => 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.',
                'user' => 3,
                'groupe' => 1
            ],
            4 => [
                'title' => 'Stalefish ',
                'description' => 'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.',
                'user' => 3,
                'groupe' => 1
            ],
            5 => [
                'title' => 'Tail Grab',
                'description' => 'Saisie de la partie arrière de la planche, avec la main arrière.',
                'user' => 3,
                'groupe' => 1
            ],
            6 => [
                'title' => 'Truck Driver',
                'description' => 'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture).',
                'user' => 3,
                'groupe' => 1
            ],
            7 => [
                'title' => '180',
                'description' => "Un 180 désigne un demi-tour, soit 180 degrés d'angle.",
                'user' => 3,
                'groupe' => 2
            ],
            8 => [
                'title' => '1080',
                'description' => 'Un 1080 ou big foot pour trois tours de rotations.',
                'user' => 3,
                'groupe' => 2
            ],
            9 => [
                'title' => '900',
                'description' => 'Un 900 pour deux tours et demi de rotations.',
                'user' => 3,
                'groupe' => 2
            ],
            10 => [
                'title' => 'Front Flip',
                'description' => 'Un flip est une rotation verticale. Le front flip consiste à faire une rotation verticale en avant.',
                'user' => 3,
                'groupe' => 3
            ],
        ];

        foreach ($tricks as $key => $value) {
            $trick = new Trick();
            $trick->setTitle($value['title']);
            $trick->setDescription($value['description']);
            $trick->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            $trick->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            //Lien avec d'autres fixtures
            $trick->setUser($this->getReference('user_' . $value['user']));
            $trick->setGroupe($this->getReference('groupe_' . $value['groupe']));

            $manager->persist($trick);

            //Add Reference
            $this->addReference('trick_' . $key, $trick);
        }

        $manager->flush();
    }
}
