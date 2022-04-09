<?php
declare(strict_types=1);

namespace App\Entity;

use OpenTribes\Core\Entity\Tile as TileInterface;
use OpenTribes\Core\Utils\Location;

final class Tile implements TileInterface
{
    public function __construct(private string $id,private string $data,private Location $location){}


    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }
}