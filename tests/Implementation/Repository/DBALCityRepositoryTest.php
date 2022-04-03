<?php
declare(strict_types=1);

namespace App\Tests\Implementation\Repository;

use App\Entity\City;
use App\Repository\Exception\CityNotFoundAtLocationException;
use App\Repository\DBALCityRepository;
use OpenTribes\Core\Exception\FailedToAddCity;
use OpenTribes\Core\Utils\Location;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversDefaultClass \App\Repository\DBALCityRepository
 */
final class DBALCityRepositoryTest extends KernelTestCase
{
    private DBALCityRepository $repository;


    public function setUp(): void
    {
        $container = $this->getContainer();
        $this->repository = $container->get(DBALCityRepository::class);
    }

    public function testUserHasNoCities(): void
    {
        $actualCounter = $this->repository->countByUsername('test_without');

        $this->assertSame(0,$actualCounter);
    }
    public function testCanCreateCity():void{
        $city = $this->repository->create(new Location(2,2));
        $this->assertNotNull($city);
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
        $this->repository->findAtLocation(new Location(-100,-100));

    }
    public function testFailedToAddCity():void{
        $this->expectException(FailedToAddCity::class);
        $city = $this->createMock(\OpenTribes\Core\Entity\City::class);
        $this->repository->add($city);
    }
    public function testCanAddCity():void
    {
        $city = new City(new Location(3,3));
        $this->repository->add($city);
        $this->repository->flush();

        $city = $this->repository->findAtLocation(new Location(3,3));
        $this->assertNotEmpty($city);
        $this->repository->remove($city);
        $this->repository->flush();
    }
}
