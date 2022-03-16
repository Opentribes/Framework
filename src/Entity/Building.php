<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\DBALBuildingRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, Index, JoinColumn, ManyToOne, Table, UniqueConstraint};
use OpenTribes\Core\Entity\Building as BuildingInterface;
use OpenTribes\Core\Enum\BuildStatus;
use OpenTribes\Core\Entity\City as CityInterface;


#[Entity(repositoryClass: DBALBuildingRepository::class)]
#[UniqueConstraint("UNQ_clot_in_city",columns: ["slot","city_id"])]
#[Index(name: "FK_city", columns: ["city_id"])]
#[Table(name: "ot_building")]
class Building implements BuildingInterface
{

    #[Id]
    #[GeneratedValue(strategy: "IDENTITY")]
    #[Column(type: "integer")]
    private int $id;

    #[Column(name:"city_id", type: "integer", options: ["unsigned"])]
    private int $cityId;

    #[Column(type: "string",length: 255,options: ["fixed"])]
    private string $slot = '';
    #[Column(type: "integer",options: ["unsigned","default"=>1])]
    private int $level = 0;
    #[Column(name:"created_at", type: "datetime", options: ["default"=>"CURRENT_TIMESTAMP"])]
    private DateTimeInterface $createdAt;
    #[ManyToOne(targetEntity: "City")]
    #[JoinColumn(name: "city_id",referencedColumnName: "id")]
    private City $city;

    #[Column(type: "string",enumType:"OpenTribes\Core\Enum\BuildStatus",options: ["default"=>"default"])]
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