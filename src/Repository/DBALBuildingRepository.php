<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Repository\BuildingRepository;
use OpenTribes\Core\Tests\Mock\Entity\MockBuilding;

final class DBALBuildingRepository implements BuildingRepository
{


    public function __construct(
        private Connection $connection,
        private EntityManagerInterface $entityManager
    ) {

    }

    public function findAllAtLocation(int $locationX, int $locationY): BuildingCollection
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $query = $queryBuilder->select('b.id,b.created_at,slot,level,status')
            ->from('ot_building', 'b')
            ->innerJoin('b', 'ot_city', 'c', $queryBuilder->expr()->eq('b.city_id', 'c.id'))
            ->where('c.location_x = :locationX AND c.location_y = :locationY')
            ->setParameters(
                [
                    'locationX' => $locationX,
                    'locationY' => $locationY,
                ]
            );
        $result = $query->executeQuery();

        while ($row = $result->fetchAssociative()) {
            dump($row);
        }
        $buildings = [];
        return new BuildingCollection($buildings);
    }

    public function findAvailable(): BuildingCollection
    {
        return new BuildingCollection();
    }

    public function userCanBuildAtLocation(int $locationX, int $locationY, string $username): bool
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $query = $queryBuilder->select('COUNT(c.id)')
            ->from('ot_city','c')
            ->innerJoin('c','se_users','u',$queryBuilder->expr()->eq('c.user_id','u.id'))
            ->where('c.location_x = :locationX AND c.location_y = :locationY AND u.username = :username')
            ->setParameters([
               'locationX'=>$locationX,
               'locationY'=>$locationY,
               'username'=>$username
            ]);
        $result = $query->executeQuery();
        return $result->fetchFirstColumn() > 0;
    }

    public function add(MockBuilding $building): void
    {
        $this->entityManager->persist($building);
    }

}
