<?php
declare(strict_types=1);

namespace App\Entity;
use OpenTribes\Core\Entity\User as UserInterface;
use Sulu\Bundle\SecurityBundle\Entity\User as SuluUser;

class User extends SuluUser implements UserInterface
{

}