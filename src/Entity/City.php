<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Entity\City as CityInterface;
use OpenTribes\Core\Entity\User;
use OpenTribes\Core\Utils\Location;


class City implements CityInterface
{
    private int $id;
    private int $locationX;
    private int $locationY;
    private DateTimeInterface $updatedAt;
    private DateTimeInterface $createdAt;
    private User $user;
    private Collection $buildings;

    public function __construct(Location $location)
    {
        $this->setLocation($location);
        $this->buildings = new ArrayCollection();
    }

    public function getLocation(): Location
    {
        return new Location($this->locationX,$this->locationY);
    }

    public function setLocation(Location $location): void
    {
        $this->locationX = $location->getX();
        $this->locationY = $location->getY();
    }

    public function getBuildings(): BuildingCollection
    {
        return new BuildingCollection($this->buildings->toArray());
    }

    public function setBuildings(BuildingCollection $buildings): void
    {
        $this->buildings = new ArrayCollection($buildings->getElements());
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}