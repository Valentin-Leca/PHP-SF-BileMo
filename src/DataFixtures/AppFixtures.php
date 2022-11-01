<?php

namespace App\DataFixtures;

use App\Repository\CustomerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Phone;
use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture {

    private UserPasswordHasherInterface $userPasswordHasher;
    private CustomerRepository $customerRepository;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, CustomerRepository $customerRepository) {

        $this->userPasswordHasher = $userPasswordHasher;
        $this->customerRepository = $customerRepository;
    }

    public function load(ObjectManager $manager): void {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 25; $i++) {
            $phone = new Phone();
            $phone->setName($faker->lastName);
            $phone->setBrand($faker->firstName);
            $phone->setDescription($faker->realText());
            $phone->setPrice($faker->randomFloat(2,100,1400));
            $phone->setCreatedAt(date_create_immutable());
            $manager->persist($phone);
        }

        for ($i = 1; $i < 13; $i++) {
            $customer = new Customer();
            $customer->setName($faker->lastName);
            $customer->setFirstname($faker->firstName);
            $customer->setLogin("Customer-".$i);
            $customer->setPassword($this->userPasswordHasher->hashPassword($customer, "Password-".$i));
            $customer->setMail($faker->email);
            $customer->setRegistrationAt(date_create_immutable());
            $customer->setRole(["ROLE_CUSTOMER"]);
            $manager->persist($customer);
        }
        $manager->flush();

        $allCustomers = $this->customerRepository->findAll();

        foreach ($allCustomers as $customers) {
            for ($i = 0; $i < 25; $i++) {
                $user = new User();
                $user->setName($faker->lastName);
                $user->setFirstname($faker->firstName);
                $user->setCreatedAt(date_create_immutable());
                $user->setCustomer($customers);
                $manager->persist($user);
            }
        }
        $manager->flush();
    }
}
