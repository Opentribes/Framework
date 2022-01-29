<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BuildingFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $building = new \OpenTribes\Core\Entity\Building('test', 10);
        $building->setLocationX(10);
        $building->setLocationY(10);
        $building->setSlot('1');
        $building->setUsername('test');

        $manager->persist($building);


        $manager->flush();
    }
}
