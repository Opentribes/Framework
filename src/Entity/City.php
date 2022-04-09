<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DBALCityRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenTribes\Core\Entity\BuildingCollection;
use OpenTribes\Core\Entity\City as CityInterface;
use OpenTribes\Core\Entity\User;
use OpenTribes\Core\Utils\Location;

#[ORM\Entity(repositoryClass: DBALCityRepository::class)]
#[ORM\UniqueConstraint('UNQ_location', columns: ['location_x', 'location_y'])]
#[ORM\Index(columns: ['user_id'], name: 'FK_user')]
#[ORM\Table(name: 'ot_city')]
class City implements CityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'user_id', type: 'integer')]
    private int $userId;

    #[ORM\Column(name: 'location_x', type: 'integer')]
    private int $locationX;
    #[ORM\Column(name: 'location_y', type: 'integer')]
    private int $locationY;

    #[ORM\Column(name: 'created_at', type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: \App\Entity\User::class, inversedBy: 'cities')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Building::class, orphanRemoval: true)]
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
