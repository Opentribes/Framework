<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tile;
use BlackScorp\SimplexNoise\Noise2D;
use OpenTribes\Core\Entity\TileCollection;
use OpenTribes\Core\Repository\MapTileRepository;
use OpenTribes\Core\Utils\Location;
use OpenTribes\Core\Utils\Viewport;
use Symfony\Component\Stopwatch\Stopwatch;

final class SimplexNoiseTileRepository implements MapTileRepository
{


    public function __construct(private readonly Noise2D $noiseGenerator, private readonly TileFactory $tileFactory, private Stopwatch $stopwatch)
    {
    }

    public function findByViewport(Viewport $viewport): TileCollection
    {
        $tileCollection = new TileCollection();
        $this->stopwatch->start('generating-map');
        for ($positionY = $viewport->getMinimumY(); $positionY <= $viewport->getMaximumY(); $positionY++) {
            for ($positionX = $viewport->getMinimumX(); $positionX <= $viewport->getMaximumX(); $positionX++) {
                $tileLocation = new Location($positionX, $positionY);
                $tileId = 'position_' . $positionY . '_' . $positionX;
                $tile = $this->tileFactory->create(id: $tileId, location: $tileLocation);
                if ($positionX < 0 || $positionY < 0) {
                    $tileCollection[] = $tile;
                    continue;
                }

                $greyValue = $this->noiseGenerator->getGreyValue($positionX, $positionY);
                $tileData = $this->tileFactory->getDataForGreyValue($greyValue);
                $tile = $this->tileFactory->create(id: $tileId, data: $tileData, location: $tileLocation);
                $tileCollection[] = $tile;
            }
        }
        $this->stopwatch->stop('generating-map');

        return $tileCollection;
    }
}