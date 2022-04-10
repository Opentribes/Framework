<?php

declare(strict_types=1);

namespace App\Tests\Implementation\Repository;


use App\Repository\TextMapTileRepository;
use OpenTribes\Core\Entity\Tile;
use OpenTribes\Core\Utils\Location;
use OpenTribes\Core\Utils\Viewport;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

final class TextMapTileRepositoryTest extends KernelTestCase
{


    public function getRepository(): TextMapTileRepository
    {
        $container = $this->getContainer();
        /** @psalm-ignore-nullable-return */
        return $container->get(TextMapTileRepository::class);
    }

    public function testFolderNotExists(): void
    {
        $this->expectException(DirectoryNotFoundException::class);
        $container = $this->getContainer();
        /** @psalm-ignore-nullable-return */
        $repository = $container->get('no_directory.tile.repository');

        $viewport = new Viewport(new Location(1, 1), 10, 10);
        $collection = $repository->findByViewport($viewport);
        $this->assertSame(0, $collection->count());
    }

    public function testFileNotExists(): void
    {
        $container = $this->getContainer();
        /** @psalm-ignore-nullable-return */
        $repository = $container->get('no_file.tile.repository');

        $viewport = new Viewport(new Location(1, 1), 10, 10);
        $collection = $repository->findByViewport($viewport);
        $this->assertSame(0, $collection->count());
    }

    public function testFileIsEmpty(): void
    {
        $container = $this->getContainer();
        /** @psalm-ignore-nullable-return */
        $repository = $container->get('empty_file.tile.repository');

        $viewport = new Viewport(new Location(0, 0), 3, 3);
        $collection = $repository->findByViewport($viewport);
        $this->assertSame(9, $collection->count());
    }

    public function testViewportIsOutsideOfMapData(): void
    {
        $repository = $this->getRepository();

        $viewport = new Viewport(new Location(1000, 1000), 3, 3);
        $collection = $repository->findByViewport($viewport);
        $this->assertSame(9, $collection->count());
    }

    public function testTilesFound(): void
    {
        $repository = $this->getRepository();
        $centerLocation = new Location(1, 1);

        $viewport = new Viewport($centerLocation, 3, 3);
        $collection = $repository->findByViewport($viewport);


        $foundTiles = $collection->filter(function (Tile $tile) use ($centerLocation) {
            $tileLocation = $tile->getLocation();
            return $tileLocation->getX() === $centerLocation->getX() && $tileLocation->getY() === $centerLocation->getY(
                );
        });
        /** @var Tile $tile */
        $tile = array_shift($foundTiles);

        $this->assertSame(9, $collection->count());
        $this->assertSame('1', $tile->getData());
    }
}