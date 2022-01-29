<?php
declare(strict_types=1);

namespace App\Tests\Implementation\Repository;

use App\Repository\DBALBuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenTribes\Core\Repository\BuildingRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class DBALBuildingRepositoryTest extends KernelTestCase
{
    public function testCannotFindBuildings():void
    {

        $container = static::getContainer();

        /** @var BuildingRepository $repository */
        $repository = $container->get(DBALBuildingRepository::class);
        $collection = $repository->findAllAtLocation(1,10);
        $this->assertSame(0,$collection->count());

    }
}
