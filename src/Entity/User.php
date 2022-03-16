<?php
declare(strict_types=1);

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;
use OpenTribes\Core\Entity\User as UserInterface;
use Sulu\Bundle\SecurityBundle\Entity\User as SuluUser;


#[Mapping\Entity]
#[Mapping\Table(name: "se_users")]
class User extends SuluUser implements UserInterface
{
    #[Mapping\OneToMany(mappedBy: "user",targetEntity: "City")]
    private Collection $cities;


    public function __construct()
    {
        parent::__construct();
        $this->cities = new ArrayCollection();
    }


    public function getCities(): ArrayCollection|Collection
    {
        return $this->cities;
    }

}