<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $groupes = [
            1 => [
                'name' => 'Grabs'
            ],
            2 => [
                'name' => 'Rotations'
            ],
            3 => [
                'name' => 'Flips'
            ],
        ];

        foreach ($groupes as $key => $value) {
            $groupe = new Group();
            $groupe->setName($value['name']);

            $manager->persist($groupe);

            //Add Reference
            $this->addReference('groupe_' . $key, $groupe);
        }

        $manager->flush();
    }
}
