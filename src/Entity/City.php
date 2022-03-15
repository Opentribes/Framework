<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Entity\City as CityInterface;
use OpenTribes\Core\Entity\User;
use OpenTribes\Core\Utils\Location;

#[Entity]
#[UniqueConstraint("UNQ_location",columns: ["location_x","location_y"])]
#[Index(name: "FK_usery", columns: ["user_id"])]
#[Table(name: "ot_city")]
class City implements CityInterface
{
    #[Id]
    #[GeneratedValue(strategy: "IDENTITY")]
    #[Column(type: "integer")]
    private int $id;

    #[Column(type: "integer")]
    private int $userId;

    #[Column(type: "integer")]
    private int $locationX;
    #[Column(type: "integer")]
    private int $locationY;

    #[Column(type: "datetime", options: ["default"=>"CURRENT_TIMESTAMP"])]
    private DateTimeInterface $createdAt;

    #[ManyToOne(targetEntity: "User")]
    #[JoinColumn(name: "user_id")]
    private User $user;
    #[OneToMany(mappedBy: "city",targetEntity: "Building")]
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
        /** @var Building $building */
        foreach($buildings as $building) {
            $building->setCity($this);
        }
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