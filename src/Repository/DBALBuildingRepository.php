<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use OpenTribes\Core\Entity\Building;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Repository\BuildingRepository;

final class DBALBuildingRepository implements BuildingRepository
{
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager){
        $this->repository = $entityManager->getRepository(Building::class);
    }

    public function findAllAtLocation(int $locationX, int $locationY): BuildingCollection
    {
      $buildings =  $this->repository->findBy([
         'locationX'=>$locationX,
         'locationY'=>$locationY,
      ]);
      return new BuildingCollection($buildings);
    }

    public function findAvailable(): BuildingCollection
    {
        return new BuildingCollection();
    }

    public function userCanBuildAtLocation(int $locationX, int $locationY, string $username): bool
    {
        return false;
    }

    public function add(Building $building): void
    {
        // TODO: Implement add() method.
    }

}