<?php


namespace App\Tests\Repository;


use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    use FixturesTrait;

    public function testCount() {
        self::bootKernel();
        $this->loadFixtures([UserFixtures::class]);
        //$users = self::$container->get(UserRepository::class)->count([]);
        //$this->assertEquals(10, $users);


        $container = self::$container;
        $em = $container->get('doctrine.orm.entity_manager');
        $users = $em->getRepository(User::class)->count([]);
        $this->assertEquals(10, $users);
    }

}