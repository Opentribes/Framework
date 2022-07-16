<?php

declare(strict_types=1);

namespace App\Repository;

use OpenTribes\Core\Entity\TileCollection;
use OpenTribes\Core\Repository\MapTileRepository;
use OpenTribes\Core\Utils\Location;
use OpenTribes\Core\Utils\Viewport;
use Symfony\Component\Finder\Finder;

final class TextMapTileRepository implements MapTileRepository
{

    public function __construct(
        private string $mapDirectory,
        private int $mapWidth,
        private TileFactory $tileFactory,
        private Finder $finder = new Finder()
    ) {
    }


    public function findByViewport(Viewport $viewport): TileCollection
    {
        $mapFiles = $this->finder->files()->in($this->mapDirectory)->name('map.txt');
        if ($mapFiles->hasResults() === false) {
            return new TileCollection();
        }
        $foundFile = null;
        foreach ($mapFiles as $file) {
            $foundFile = $file;
        }
        return $this->loadMapData($foundFile, $viewport);
    }

    private function loadMapData(\SplFileInfo $mapFile, Viewport $viewport): TileCollection
    {
        $tileCollection = new TileCollection();

        $stream = fopen($mapFile->getRealPath(), 'rb');

        for ($positionY = $viewport->getMinimumY(); $positionY <= $viewport->getMaximumY(); $positionY++) {
            for ($positionX = $viewport->getMinimumX(); $positionX <= $viewport->getMaximumX(); $positionX++) {
                $tileLocation = new Location($positionX, $positionY);
                $tileId = 'position_' . $positionY . '_' . $positionX;
                $tile = $this->tileFactory->create(id: $tileId, location: $tileLocation);
                if ($positionX < 0 || $positionY < 0) {
                    $tileCollection[] = $tile;
                    continue;
                }

                $index = ($this->mapWidth * $positionY + $positionX);

                fseek($stream, $index);
                $tileData = fread($stream, 1);
                if (!$tileData) {
                    $tileCollection[] = $tile;
                    continue;
                }
                $data = (string)bindec($tileData);

                $tile = $this->tileFactory->create($tileId, $data, $tileLocation);
                $tileCollection[] = $tile;
            }
        }

        fclose($stream);
        return $tileCollection;
    }


}