<?php

namespace App\DataFixtures;

use App\Entity\Faction;
use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $personne1 = new Personne;
        $personne1->setNom("Skywalker")
                  ->setPrenom("Luc")
                  ->setFaction("Jedi")
        ;
        $manager->persist($personne1);

        $personne2 = new Personne;
        $personne2->setNom("Skywalker")
                  ->setPrenom("Anakin")
                  ->setFaction("Sith")
        ;
        $manager->persist($personne2);
        
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
