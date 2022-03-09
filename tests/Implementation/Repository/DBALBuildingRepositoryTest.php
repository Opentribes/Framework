<?php
declare(strict_types=1);

namespace App\Tests\Implementation\Repository;

use App\Repository\DBALBuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenTribes\Core\Tests\Mock\Entity\MockCity;
use OpenTribes\Core\Repository\BuildingRepository;
use OpenTribes\Core\Tests\Mock\Entity\MockBuilding;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversDefaultClass BuildingRepository
 */
final class DBALBuildingRepositoryTest extends KernelTestCase
{
    private BuildingRepository $repository;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $container = $this->getContainer();
        /** @var BuildingRepository $repository */
        $this->repository = $container->get(DBALBuildingRepository::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);

    }

    public function testCannotFindBuildings():void
    {
        $collection = $this->repository->findAllAtLocation(1,10);
        $this->assertSame(0,$collection->count());

    }
    public function testBuildingsFound():void{

        $collection = $this->repository->findAllAtLocation(10,10);

        $this->assertSame(1,$collection->count());
    }

    public function testUseCannotBuildAtLocation():void{
        $result = $this->repository->userCanBuildAtLocation(1,1,'test');
        $this->assertFalse($result);
    }
    public function testUserCanBuildAtLocation(): void{
        $result = $this->repository->userCanBuildAtLocation(10,10,'test');
        $this->assertTrue($result);
    }
    public function testCanAddBuilding():void{

        $city = $this->entityManager->getRepository(MockCity::class)->findOneBy([
            'locationX'=>2,
            'locationY'=>2,
        ]);

        $building = new MockBuilding('test',12);
        $building->setCity($city);

        $building->setSlot('2');

        $this->repository->add($building);

        $this->entityManager->flush();

        $collection = $this->repository->findAllAtLocation(2,2);
        $this->assertSame(1,$collection->count());

        $this->entityManager->remove($collection->first());

        $this->entityManager->flush();
    }

    public function testCanListAvailableBuildings():void{
        $result = $this->repository->findAvailable();
        $this->assertSame(0,$result->count());
    }
}
