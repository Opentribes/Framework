<?php

declare(strict_types=1);

namespace App\Entity;


use App\Repository\DBALUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenTribes\Core\Entity\CityCollection;
use OpenTribes\Core\Entity\User as UserInterface;
use Sulu\Bundle\SecurityBundle\Entity\User as SuluUser;


#[ORM\Entity(repositoryClass: DBALUserRepository::class)]
#[ORM\Table(name: "se_users")]
class User extends SuluUser implements UserInterface
{
    #[ORM\OneToMany(mappedBy: "user", targetEntity: City::class)]
    private Collection $cities;


    public function __construct()
    {
        parent::__construct();
        $this->cities = new ArrayCollection();
    }


    public function getCities(): CityCollection
    {
        return new CityCollection($this->cities->toArray());
    }

    public function getUsername():string
    {
        return $this->username;
    }

}