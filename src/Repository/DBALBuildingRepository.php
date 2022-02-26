<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Parameter;
use OpenTribes\Core\Entity\Building;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Entity\City;
use OpenTribes\Core\Repository\BuildingRepository;
use Sulu\Bundle\SecurityBundle\Entity\User;

final class DBALBuildingRepository implements BuildingRepository
{
    private EntityRepository $repository;
    private EntityRepository $cityRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Building::class);
        $this->cityRepository = $entityManager->getRepository(City::class);
    }

    public function findAllAtLocation(int $locationX, int $locationY):BuildingCollection
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $query = $queryBuilder->select('building')
            ->from(Building::class,'building')
            ->innerJoin('building.city','city')
            ->where('city.locationX = :locationX AND city.locationY = :locationY')
            ->setParameters(new ArrayCollection(
                [
                    new Parameter(':locationX',$locationX),
                    new Parameter(':locationY',$locationX),
                ]
            ))->getQuery();

        $buildings = $query->getResult();
        return new BuildingCollection($buildings);
    }

    public function findAvailable(): BuildingCollection
    {
        return new BuildingCollection();
    }

    public function userCanBuildAtLocation(int $locationX, int $locationY, string $username): bool
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $query = $queryBuilder->select('COUNT(city.id)')
            ->from(City::class,'city')
            ->innerJoin('city.user','user')
            ->where('city.locationX = :locationX AND city.locationY = :locationY AND user.username = :username')
            ->setParameters(new ArrayCollection(
                [
                    new Parameter(':locationX',$locationX),
                    new Parameter(':locationY',$locationX),
                    new Parameter(':username',$username)
                ]
            ))->getQuery();
        return $query->getSingleScalarResult() > 0;

    }

    public function add(Building $building): void
    {
        $this->entityManager->persist($building);
    }

}
