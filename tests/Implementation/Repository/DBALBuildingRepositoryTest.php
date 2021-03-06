<?php
declare(strict_types=1);

namespace App\Tests\Implementation\Repository;

use App\Entity\Building;
use App\Entity\City;
use App\Repository\DBALBuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenTribes\Core\Repository\BuildingRepository;
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


        /**
         * @psalm-ignore-nullable-return
         * @var BuildingRepository $repository
         */
        $this->repository = $container->get(DBALBuildingRepository::class);
        /** @psalm-ignore-nullable-return */
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

        /** @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy([
            'locationX'=>2,
            'locationY'=>2,
        ]);

        $building = new Building('test',12);
        $building->setCity($city);

        $building->setSlot('2');

        $this->repository->add($building);

        $this->entityManager->flush();

        $collection = $this->repository->findAllAtLocation(2,2);
        $this->assertSame(1,$collection->count());

        /** @var Building $entity */
        $entity =$collection->first();
        $this->entityManager->remove($entity);

        $this->entityManager->flush();
    }

    public function testCanListAvailableBuildings():void{
        $result = $this->repository->findAvailable();
        $this->assertSame(0,$result->count());
    }
}
