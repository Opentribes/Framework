<?php

declare(strict_types=1);

namespace App\Repository;


use App\Entity\Building;
use App\Entity\City;
use OpenTribes\Core\Entity\Building as BuildingInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use OpenTribes\Core\Entity\BuildingCollection;

use OpenTribes\Core\Repository\BuildingRepository;

final class DBALBuildingRepository implements BuildingRepository
{

    private EntityRepository $buildingRepository;
    private EntityRepository $cityRepository;
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->buildingRepository = $this->entityManager->getRepository(Building::class);
        $this->cityRepository = $this->entityManager->getRepository(City::class);
    }

    public function findAllAtLocation(int $locationX, int $locationY): BuildingCollection
    {
        $cityAtLocation = $this->cityRepository->findOneBy([
                'locationX'=>$locationX,
                'locationY'=>$locationY,
        ]);
        if(!$cityAtLocation){
            return new BuildingCollection([]);
        }
        return $cityAtLocation->getBuildings();
    }

    public function findAvailable(): BuildingCollection
    {
        return new BuildingCollection();
    }

    public function userCanBuildAtLocation(int $locationX, int $locationY, string $username): bool
    {
        $cityAtLocation = $this->cityRepository->findOneBy([
            [
                'locationX'=>$locationX,
                'locationY'=>$locationY,
                'user'=>[
                    'username'=>$username
                ]
            ]
        ]);
        return $cityAtLocation !== null;
    }

    public function add(BuildingInterface $building): void
    {
        $this->entityManager->persist($building);
    }

}
