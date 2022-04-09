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
use OpenTribes\Core\Entity\CityCollection;
use OpenTribes\Core\Exception\FailedToAddCity;
use OpenTribes\Core\Repository\CityRepository;
use OpenTribes\Core\Utils\Location;
use OpenTribes\Core\Utils\Viewport;

use function Doctrine\ORM\QueryBuilder;

final class DBALCityRepository extends ServiceEntityRepository implements CityRepository
{
    public function __construct(
        protected ManagerRegistry $registry
    ) {
        parent::__construct($registry, City::class);
    }

    public function __destruct()
    {
        $this->flush();
    }

    public function countByUsername(string $username): int
    {
        $qb = $this->createQueryBuilder('city');
        $qb->select('COUNT(city.id)')
            ->join('city.user', 'user')
            ->where(
                $qb->expr()->eq('user.username', ':username'),
            )
            ->setParameter('username', $username, Types::STRING);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function add(CityInterface $city): void
    {
        try {
            $this->_em->persist($city);
        } catch (\Throwable $exception) {
            throw new FailedToAddCity('Failed to add city to repository', (int) $exception->getCode(), $exception);
        }
    }

    public function countAtLocation(Location $location): int
    {
        $qb = $this->createQueryBuilder('city');
        $qb->select('COUNT(city.id)')
            ->where(
                /** @no-named-arguments */
                $qb->expr()->andX(
                    $qb->expr()->eq('city.locationX', ':locationX'),
                    $qb->expr()->eq('city.locationY', ':locationY'),
                )
            )
            ->setParameter('locationX', $location->getX(), Types::INTEGER)
            ->setParameter('locationY', $location->getY(), Types::INTEGER);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function create(Location $location): CityInterface
    {
        return new City($location);
    }

    public function findAtLocation(Location $location): CityInterface
    {
        $qb = $this->createQueryBuilder('city');
        $qb->select('city')
            ->where(
                /** @no-named-arguments */
                $qb->expr()->andX(
                    $qb->expr()->eq('city.locationX', ':locationX'),
                    $qb->expr()->eq('city.locationY', ':locationY'),
                )
            )
            ->setParameter('locationX', $location->getX(), Types::INTEGER)
            ->setParameter('locationY', $location->getY(), Types::INTEGER);
        $query = $qb->getQuery();
        try {
            return $query->getSingleResult();
        } catch (NoResultException $exception) {
            throw new CityNotFoundAtLocationException(
                sprintf('City not found at location %d/%d', $location->getY(), $location->getX())
            );
        }
    }

    public function findByViewport(Viewport $viewport): CityCollection
    {
        $qb = $this->createQueryBuilder('city');
        $qb->select('city')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->between('city.locationX',':minimumX',':maximumX'),
                    $qb->expr()->between('city.locationY',':minimumY',':maximumY')
                )
            )
            ->setParameter('minimumX', $viewport->getMinimumX(), Types::INTEGER)
            ->setParameter('maximumX', $viewport->getMinimumX(), Types::INTEGER)
            ->setParameter('minimumY', $viewport->getMinimumY(), Types::INTEGER)
            ->setParameter('maximumY', $viewport->getMaximumY(), Types::INTEGER);

        return new CityCollection(...$qb->getQuery()->getResult());
    }


    public function remove(CityInterface $city): void
    {
        $this->_em->remove($city);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}
