<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tile;
use BlackScorp\SimplexNoise\Noise2D;
use OpenTribes\Core\Entity\TileCollection;
use OpenTribes\Core\Repository\MapTileRepository;
use OpenTribes\Core\Utils\Location;
use OpenTribes\Core\Utils\Viewport;

final class SimplexNoiseTileRepository implements MapTileRepository
{


    public function __construct(private Noise2D $noiseGenerator)
    {
    }

    public function findByViewport(Viewport $viewport): TileCollection
    {
        $tileCollection = new TileCollection();

        for ($positionY = $viewport->getMinimumY(); $positionY <= $viewport->getMaximumY(); $positionY++) {
            for ($positionX = $viewport->getMinimumX(); $positionX <= $viewport->getMaximumX(); $positionX++) {
                $tileLocation = new Location($positionX, $positionY);
                $tileId = 'position_' . $positionY . '_' . $positionX;
                $tile = new Tile($tileId, '0000', $tileLocation);
                if ($positionX < 0 || $positionY < 0) {
                    $tileCollection[] = $tile;
                    continue;
                }

                $greyValue = $this->noiseGenerator->getGreyValue($positionX, $positionY);

                $tile = $this->convertGreyValueToTile($greyValue, $tileId, $tileLocation);
                $tileCollection[] = $tile;
            }
        }

        return $tileCollection;
    }

    private function convertGreyValueToTile(int $greyValue, string $tileId, Location $location): Tile
    {
        $data = '0002';


        if ($greyValue > 60) {
            $data = '0001';
        }
        if ($greyValue > 190) {
            $data = '0003';
        }

        return new Tile($tileId, $data, $location);
    }
}