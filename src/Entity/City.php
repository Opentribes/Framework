<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Entity\City as CityInterface;
use OpenTribes\Core\Entity\User;

use OpenTribes\Core\Utils\Location;

#[Mapping\Entity]
#[Mapping\UniqueConstraint("UNQ_location", columns: ["location_x", "location_y"])]
#[Mapping\Index(columns: ["user_id"], name: "FK_user")]
#[Mapping\Table(name: "ot_city")]
class City implements CityInterface
{
    #[Mapping\Id]
    #[Mapping\GeneratedValue(strategy: "IDENTITY")]
    #[Mapping\Column(type: "integer")]
    private int $id;

    #[Mapping\Column(name: "user_id", type: "integer")]
    private int $userId;

    #[Mapping\Column(name: "location_x", type: "integer")]
    private int $locationX;
    #[Mapping\Column(name: "location_y", type: "integer")]
    private int $locationY;

    #[Mapping\Column(name: "created_at", type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private DateTimeInterface $createdAt;

    #[Mapping\ManyToOne(targetEntity: "User", inversedBy: "cities")]
    #[Mapping\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private User $user;

    #[Mapping\OneToMany(mappedBy: "city", targetEntity: "Building", orphanRemoval: true)]
    private Collection $buildings;

    public function __construct(Location $location)
    {
        $this->setLocation($location);
        $this->buildings = new ArrayCollection();
    }

    public function getLocation(): Location
    {
        return new Location($this->locationX, $this->locationY);
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
        foreach ($buildings as $building) {
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