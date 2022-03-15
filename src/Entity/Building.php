<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use OpenTribes\Core\Entity\Building as BuildingInterface;
use OpenTribes\Core\Enum\BuildStatus;
use OpenTribes\Core\Entity\City as CityInterface;

#[Entity]
#[UniqueConstraint("UNQ_clot_in_city",columns: ["slot","city_id"])]
#[Index(name: "FK_city", columns: ["city_id"])]
#[Table(name: "ot_building")]
class Building implements BuildingInterface
{

    #[Id]
    #[GeneratedValue(strategy: "IDENTITY")]
    #[Column(type: "integer")]
    private int $id;

    #[Column(type: "integer", options: ["unsigned"])]
    private int $cityId;

    #[Column(type: "string",length: 255,options: ["fixed"])]
    private string $slot = '';
    #[Column(type: "integer",options: ["unsigned","default"=>1])]
    private int $level = 0;
    #[Column(type: "datetime", options: ["default"=>"CURRENT_TIMESTAMP"])]
    private DateTimeInterface $createdAt;
    #[ManyToOne(targetEntity: "City", cascade: ["all"],fetch: "EAGER")]
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