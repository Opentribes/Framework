<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\City;
use App\Repository\Exception\CityNotFoundAtLocationException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use OpenTribes\Core\Entity\City as CityInterface;
use OpenTribes\Core\Repository\CityRepository;
use OpenTribes\Core\Utils\Location;

final class DBALCityRepository extends ServiceEntityRepository implements CityRepository
{

    public function __construct(
        protected ManagerRegistry $registry
    ) {
        parent::__construct($registry, City::class);
    }

    public function countByUsername(string $username): int
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('COUNT(c.id)')
            ->join('c.user', 'u')
            ->where(
                $qb->expr()->eq('u.username', ':username'),
            )
            ->setParameter('username', $username, Types::STRING);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function add(CityInterface $city): void
    {
        $this->_em->persist($city);
    }

    public function countAtLocation(Location $location): int
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('COUNT(c.id)')
            ->where(
                $qb->expr()->eq('c.locationX', ':locationX'),
                $qb->expr()->eq('c.locationY', ':locationY'),
            )
            ->setParameter('locationX', $location->getX(), Types::INTEGER)
            ->setParameter('locationY', $location->getY(), Types::INTEGER);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findAtLocation(Location $location): CityInterface
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c')
            ->where(
                $qb->expr()->eq('c.locationX', ':locationX'),
                $qb->expr()->eq('c.locationY', ':locationY'),
            )
            ->setParameter('locationX', $location->getX(), Types::INTEGER)
            ->setParameter('locationY', $location->getY(), Types::INTEGER);
        $query = $qb->getQuery();
        try{
           return $query->getSingleResult();
        }catch (NoResultException $exception){
            throw new CityNotFoundAtLocationException(sprintf("City not found at location %d/%d",$location->getY(),$location->getX()));
        }

    }

}