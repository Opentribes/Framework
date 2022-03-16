<?php
declare(strict_types=1);

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use OpenTribes\Core\Entity\User as UserInterface;
use Sulu\Bundle\SecurityBundle\Entity\User as SuluUser;


#[Entity]
#[Table(name: "se_users")]
class User extends SuluUser implements UserInterface
{
    #[OneToMany(mappedBy: "user",targetEntity: "City")]
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