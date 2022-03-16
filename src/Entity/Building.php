<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\DBALBuildingRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping;
use OpenTribes\Core\Entity\Building as BuildingInterface;
use OpenTribes\Core\Enum\BuildStatus;
use OpenTribes\Core\Entity\City as CityInterface;


#[Mapping\Entity(repositoryClass: DBALBuildingRepository::class)]
#[Mapping\UniqueConstraint("UNQ_clot_in_city",columns: ["slot","city_id"])]
#[Mapping\Index(columns: ["city_id"],name: "FK_city")]
#[Mapping\Table(name: "ot_building")]
class Building implements BuildingInterface
{

    #[Mapping\Id]
    #[Mapping\GeneratedValue(strategy: "IDENTITY")]
    #[Mapping\Column(type: "integer")]
    private int $id;

    #[Mapping\Column(name:"city_id", type: "integer", options: ["unsigned"])]
    private int $cityId;

    #[Mapping\Column(type: "string",length: 255,options: ["fixed"])]
    private string $slot = '';
    #[Mapping\Column(type: "integer",options: ["unsigned","default"=>1])]
    private int $level = 0;
    #[Mapping\Column(name:"created_at", type: "datetime", options: ["default"=>"CURRENT_TIMESTAMP"])]
    private DateTimeInterface $createdAt;
    #[Mapping\ManyToOne(targetEntity: "City",inversedBy: "buildings")]
    #[Mapping\JoinColumn(name: "city_id",referencedColumnName: "id")]
    private City $city;

    #[Mapping\Column(type: "string",enumType:"OpenTribes\Core\Enum\BuildStatus",options: ["default"=>"default"])]
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