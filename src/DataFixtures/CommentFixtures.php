<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($c = 0; $c < mt_rand(100, 120); $c++) {
            //Lien avec d'autres fixtures
            $user = $this->getReference('user_' . $faker->numberBetween(1, 10));
            $trick = $this->getReference('trick_' . $faker->numberBetween(1, 10));

            $comment = new Comment();
            $comment->setUser($user);
            $comment->setTrick($trick);
            $comment->setContent($faker->sentence(mt_rand(1, 5)));
            $comment->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            $comment->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            $manager->persist($comment);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            GroupFixtures::class,
            TrickFixtures::class,
            MediaFixtures::class,
        ];
    }
}
