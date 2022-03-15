<?php
declare(strict_types=1);

namespace App\Entity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use OpenTribes\Core\Entity\User as UserInterface;
use Sulu\Bundle\SecurityBundle\Entity\User as SuluUser;

#[Entity]
#[Table(name: "se_users")]
class User extends SuluUser implements UserInterface
{

}