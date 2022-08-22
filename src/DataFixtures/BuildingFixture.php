<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Building;
use App\Entity\City;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Utils\Location;
use Sulu\Bundle\ContactBundle\Entity\Contact;

final class BuildingFixture extends Fixture implements  FixtureGroupInterface
{
    private ObjectManager $manager;

    public static function getGroups(): array
    {
        return ['test'];
    }


    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;
        $user = $this->createUser('test_without');

        $user = $this->createUser('test');
        $city = new City(new Location(10, 10));
        $city->setUser($user);
        $manager->persist($city);

        $buildingCollection = new BuildingCollection();
        $building = new Building('test', 10);
        $building->setSlot('1');
        $buildingCollection[] = $building;

        $city->setBuildings($buildingCollection);

        $manager->persist($building);

        $city = new City(new Location(2, 2));
        $manager->persist($city);

        $manager->flush();
    }
    private function createUser(string $username): User
    {
        $userContact = new Contact();
        $userContact->setFirstName($username);
        $userContact->setLastName($username);
        $userContact->setMainEmail($username.'@local.test');
        $this->manager->persist($userContact);
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($username);
        $user->setLocale('de');
        $user->setSalt($username);
        $user->setContact($userContact);
        $this->manager->persist($user);
        return $user;
    }
}
