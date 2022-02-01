<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use OpenTribes\Core\Entity\Building;

final class BuildingFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $building = new Building('test', 10);
        $building->setLocationX(10);
        $building->setLocationY(10);
        $building->setSlot('1');
        $building->setUsername('test');

        $manager->persist($building);

        $manager->flush();
    }
}
