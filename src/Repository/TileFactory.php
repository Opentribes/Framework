<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tile;
use League\Flysystem\FilesystemOperator;
use OpenTribes\Core\Entity\Tile as TileInterface;
use OpenTribes\Core\Entity\TileCollection;
use OpenTribes\Core\Utils\Location;
use SplFixedArray;
use Symfony\Component\Stopwatch\Stopwatch;


final class TileFactory
{
    private const EMPTY_DATA = '0';
    private const GRADIENT_FILE_NAME = 'gradient.png';
    private TileCollection $tilesByRGBIndex;

    private \SplFixedArray $gradientData;
    private bool $gradientLoaded = false;

    public function __construct(private readonly FilesystemOperator $mapStorage,private readonly Stopwatch $stopwatch)
    {
        $this->tilesByRGBIndex = new TileCollection();

        $this->tilesByRGBIndex['116-163-63'] = new Tile('gras', '1');
        $this->tilesByRGBIndex['42-88-79'] = new Tile('gras2', '1');
        $this->tilesByRGBIndex['110-184-168'] = new Tile('sea', '2');
        $this->tilesByRGBIndex['238-156-93'] = new Tile('mountain', '3');
        $this->tilesByRGBIndex['252-255-192'] = new Tile('mountain2', '3');
        $this->gradientData = new \SplFixedArray(255);
    }

    public function getDataForGreyValue(int $greyValue): string
    {
        if ($this->gradientLoaded === false) {
            $this->gradientData = $this->loadGradient($this->gradientData);
        }

        return $this->gradientData[$greyValue];
    }

    private function loadGradient(SplFixedArray $gradientData): \SplFixedArray
    {
        $this->stopwatch->start('load-gradient');
        $gradient = $this->mapStorage->read(self::GRADIENT_FILE_NAME);
        $image = imagecreatefromstring($gradient);

        for ($left = 0; $left < 255; $left++) {
            $gradientColor = imagecolorat($image, $left, 0);
            $red = ($gradientColor >> 16) & 0xFF;
            $green = ($gradientColor >> 8) & 0xFF;
            $blue = $gradientColor & 0xFF;
            $tile = $this->findTileByRGB($red, $green, $blue);
            $gradientData[$left] = $tile->getData();
        }
        $this->gradientLoaded = true;
        $this->stopwatch->stop('load-gradient');
        return $gradientData;
    }

    public function create(string $id = 'empty', ?string $data = null, ?Location $location = null): TileInterface
    {
        if ($data === null) {
            $data = self::EMPTY_DATA;
        }
        return new Tile($id, $data, $location);
    }

    public function findTileByRGB(int $red, int $green, int $blue): TileInterface
    {
        $index = sprintf('%d-%d-%d', $red, $green, $blue);
        return $this->tilesByRGBIndex[$index] ?? $this->create();
    }

}