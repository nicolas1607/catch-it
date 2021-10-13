<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\Album;
use App\Entity\Address;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passHasher;

    public function __construct(UserPasswordHasherInterface $passHasher)
    {
        $this->passHasher = $passHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // User Entity
        for ($i = 0; $i < 10; $i++) {
            $address = new Address;
            $address->setStreet($faker->streetAddress)
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry($faker->country);
            $user = new User;
            $user->setEmail($faker->email)
                ->setPassword($this->passHasher->hashPassword($user, $faker->password))
                ->setRoles([])
                ->setAddress($address);
            $manager->persist($user);
        }

        // Item Entity
        for ($i = 0; $i < 30; $i++) {
            $item = new Item;
            $item->setName($faker->word);
            $manager->persist($item);
        }

        // Album Entity
        for ($k = 0; $k < 10; $k++) {
            $album = new Album;
            $album->setName($faker->word);
            $manager->persist($album);
            $i++;
        }

        $manager->persist($this->createAdminUser());
        $manager->flush();
    }

    public function createAdminUser(): User
    {
        $faker = Factory::create();

        $admin = new User;
        $admin->setEmail('nicolas160796@gmail.com')
            ->setPassword($this->passHasher->hashPassword($admin, 'test'))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('Nicolas')
            ->setLastname('Mormiche');
        return $admin;
    }
}
