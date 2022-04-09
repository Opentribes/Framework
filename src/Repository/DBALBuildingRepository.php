<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use OpenTribes\Core\Entity\Building as BuildingInterface;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Repository\BuildingRepository;

final class DBALBuildingRepository extends ServiceEntityRepository implements BuildingRepository
{
    public function __construct(
        protected ManagerRegistry $registry
    ) {
        parent::__construct($registry, Building::class);
    }

    public function findAllAtLocation(int $locationX, int $locationY): BuildingCollection
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('b')
            ->join('b.city', 'c')
            ->where(
                /** @no-named-arguments */
                $qb->expr()->andX(
                    $qb->expr()->eq('c.locationX', ':locationX'),
                    $qb->expr()->eq('c.locationY', ':locationY'),
                )
            )
            ->setParameter('locationX', $locationX, Types::INTEGER)
            ->setParameter('locationY', $locationY, Types::INTEGER);
        return new BuildingCollection($qb->getQuery()->getResult());
    }

    public function findAvailable(): BuildingCollection
    {
        return new BuildingCollection();
    }

    public function userCanBuildAtLocation(int $locationX, int $locationY, string $username): bool
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('COUNT(c.id)')
            ->join('b.city', 'c')
            ->join('c.user', 'u')
            ->where(
                /** @no-named-arguments */
                $qb->expr()->andX(
                    $qb->expr()->eq('c.locationX', ':locationX'),
                    $qb->expr()->eq('c.locationY', ':locationY'),
                    $qb->expr()->eq('u.username', ':username'),
                )
            )
            ->setParameter('locationX', $locationX, Types::INTEGER)
            ->setParameter('locationY', $locationY, Types::INTEGER)
            ->setParameter('username', $username, Types::STRING);

        return $qb->getQuery()->getSingleScalarResult() !== 0;
    }

    public function add(BuildingInterface $building): void
    {
        $this->_em->persist($building);
    }
}
