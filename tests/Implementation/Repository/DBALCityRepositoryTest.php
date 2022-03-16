<?php
declare(strict_types=1);

namespace App\Tests\Implementation\Repository;

use App\Entity\City;
use App\Repository\Exception\CityNotFoundAtLocationException;
use App\Repository\DBALCityRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenTribes\Core\Repository\CityRepository;
use OpenTribes\Core\Utils\Location;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversDefaultClass \App\Repository\DBALCityRepository
 */
final class DBALCityRepositoryTest extends KernelTestCase
{
    private CityRepository $repository;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $container = $this->getContainer();
        /** @var DBALCityRepository $repository */
        $this->repository = $container->get(DBALCityRepository::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public function testUserHasNoCities(): void
    {
        $actualCounter = $this->repository->countByUsername('test_without');

        $this->assertSame(0,$actualCounter);
    }

    public function testUserHasACity(): void
    {
        $actualCounter = $this->repository->countByUsername('test');

        $this->assertSame(1,$actualCounter);
    }

    public function testCityNotFound():void{
        $actualCount = $this->repository->countAtLocation(new Location(-1000,-1000));
        $this->assertSame(0,$actualCount);
    }
    public function testCityFound():void{
        $actualCount = $this->repository->countAtLocation(new Location(2,2));
        $this->assertSame(1,$actualCount);
    }

    public function testCityEntityFound(): void
    {
        $city = $this->repository->findAtLocation(new Location(2,2));
        $this->assertSame(2,$city->getLocation()->getY());
        $this->assertSame(2,$city->getLocation()->getX());
    }
    public function testCityEntityNotFound(): void{
        $this->expectException(CityNotFoundAtLocationException::class);
        $city = $this->repository->findAtLocation(new Location(-100,-100));

    }

    public function testCanAddCity():void
    {
        $city = new City(new Location(3,3));
        $this->repository->add($city);
        $this->entityManager->flush();

        $city = $this->repository->findAtLocation(new Location(3,3));
        $this->assertNotEmpty($city);

        $this->entityManager->remove($city);
        $this->entityManager->flush();
    }
}
