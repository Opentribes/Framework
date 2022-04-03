<?php

declare(strict_types=1);

namespace App\Repository;

use OpenTribes\Core\Entity\User as UserInterface;
use OpenTribes\Core\Repository\UserRepository;
use Sulu\Bundle\SecurityBundle\Entity\UserRepository as SuluUserRepository;

final class DBALUserRepository extends SuluUserRepository implements UserRepository
{

    public function findByUsername(string $username): UserInterface
    {
        $qb = $this->createQueryBuilder('user')
            ->select('user', 'cities')
            ->leftJoin('user.cities', 'cities')
            ->where('user.username=:username');

        $query = $qb->getQuery();
        $query->setParameter('username', $username);

        return $query->getSingleResult();
    }

}