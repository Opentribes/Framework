<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Building;
use App\Entity\City;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Utils\Location;
use Sulu\Bundle\ContactBundle\Entity\Contact;


final class BuildingFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $userContact = new Contact();
        $userContact->setFirstName('Test');
        $userContact->setLastName('Test');
        $userContact->setMainEmail('foo@bar.test');
        $manager->persist($userContact);
        $user = new User();
        $user->setUsername('test');
        $user->setPassword('test');
        $user->setLocale('de');
        $user->setSalt('test');
        $user->setContact($userContact);
        $manager->persist($user);

        $city = new City(new Location(10,10));
        $city->setUser($user);
        $manager->persist($city);


        $buildingCollection = new BuildingCollection();
        $building = new Building('test', 10);
        $building->setSlot('1');
        $buildingCollection[]=$building;

        $city->setBuildings($buildingCollection);

        $manager->persist($building);

        $city = new City(new Location(2,2));
        $manager->persist($city);

        $manager->flush();
    }
}
