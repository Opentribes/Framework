<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DBALBuildingRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use OpenTribes\Core\Entity\Building as BuildingInterface;
use OpenTribes\Core\Entity\City as CityInterface;
use OpenTribes\Core\Enum\BuildStatus;

#[ORM\Entity(repositoryClass: DBALBuildingRepository::class)]
#[ORM\UniqueConstraint('UNQ_clot_in_city', columns: ['slot','city_id'])]
#[ORM\Index(columns: ['city_id'], name: 'FK_city')]
#[ORM\Table(name: 'ot_building')]
class Building implements BuildingInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name:'city_id', type: 'integer', options: ['unsigned' => true])]
    private int $cityId;

    #[ORM\Column(type: 'string', length: 255, options: ['fixed' => true])]
    private string $slot = '';
    #[ORM\Column(type: 'integer', options: ['unsigned' => true,'default' => 1])]
    private int $level = 0;
    #[ORM\Column(name:'created_at', type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $createdAt;
    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'buildings')]
    #[ORM\JoinColumn(name: 'city_id', referencedColumnName: 'id')]
    private CityInterface $city;

    #[ORM\Column(type: 'string', enumType: BuildStatus::class, options: ['default' => 'default'])]
    private BuildStatus $status;

    public function __construct(private string $name, private int $maximumLevel)
    {
        $this->status = BuildStatus::default;
    }

    public function setSlot(string $slot): void
    {
        $this->slot = $slot;
    }

    public function getSlot(): string
    {
        return $this->slot;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function upgrade(): void
    {
        $this->level++;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function downgrade(): void
    {
        $this->level--;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getStatus(): BuildStatus
    {
        return $this->status;
    }

    public function setStatus(BuildStatus $status): void
    {
        $this->status = $status;
    }

    public function getMaximumLevel(): int
    {
        return $this->maximumLevel;
    }

    public function getCity(): CityInterface
    {
        return $this->city;
    }

    public function setCity(CityInterface $city): void
    {
        $this->city = $city;
    }
}
