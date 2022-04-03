<?php
declare(strict_types=1);

namespace App\Tests\Implementation\Repository;


use App\Repository\DBALUserRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DBALUserRepositoryTest extends KernelTestCase
{
    private DBALUserRepository $repository;


    public function setUp(): void
    {
        $container = $this->getContainer();
        $this->repository = $container->get('dbal.user.repository');
    }

    public function testUsernameNotExists():void{
        $this->expectException(NoResultException::class);
        $this->repository->findByUsername('not_existing_user');
    }
    public function testUsernameExists():void{

       $user =  $this->repository->findByUsername('test');
       $this->assertSame('test',$user->getUsername());
    }
}