<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tile;
use OpenTribes\Core\Entity\TileCollection;
use OpenTribes\Core\Repository\MapTileRepository;
use OpenTribes\Core\Utils\Location;
use OpenTribes\Core\Utils\Viewport;
use Symfony\Component\Finder\Finder;

final class TextMapTileRepository implements MapTileRepository
{
    private Finder $finder;
    private const EMPTY_DATA = '0';

    public function __construct(private string $storageDir, private int $mapWidth, private int $chunkSize)
    {
        $this->finder = Finder::create();
    }

    public function findByViewport(Viewport $viewport): TileCollection
    {
        $mapFiles = $this->finder->files()->in($this->storageDir . '/map')->name('main.txt');
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
        $stream = fopen($mapFile->getRealPath(), 'r');

        for ($positionY = $viewport->getMinimumY(); $positionY <= $viewport->getMaximumY(); $positionY++) {
            for ($positionX = $viewport->getMinimumX(); $positionX <= $viewport->getMaximumX(); $positionX++) {
                $tileLocation = new Location($positionX, $positionY);
                $tileId = 'position_'.$positionY . '_' . $positionX;
                $tile = new Tile($tileId, str_repeat(self::EMPTY_DATA, $this->chunkSize), $tileLocation);
                if ($positionX < 0 || $positionY < 0) {
                    $tileCollection[]=$tile;
                    continue;
                }

                $index = ($this->mapWidth * $positionY + $positionX) * $this->chunkSize;
                $result = fseek($stream, $index);
                if ($result !== 0) {
                    $tileCollection[]=$tile;
                    continue;
                }
                $tileData = fread($stream, $this->chunkSize);
                $tile = new Tile($tileId, $tileData, $tileLocation);
                $tileCollection[]=$tile;
            }
        }

        fclose($stream);
        return $tileCollection;
    }


}