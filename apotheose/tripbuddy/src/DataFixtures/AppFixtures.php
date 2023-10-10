<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Itinerary;
use App\Entity\Step;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création de l'administrateur
        $admin = new User();
        $admin->setFirstname($faker->firstName());
        $admin->setLastname($faker->lastName());
        $admin->setEmail("admin@oclock.io");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, "admin"));
        $manager->persist($admin);

        // Création de l'utilisateur "user"
        $user = new User();
        $user->setEmail("user@oclock.io");
        $user->setFirstname($faker->firstName());
        $user->setLastname($faker->lastName());
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->passwordHasher->hashPassword($user, "user"));
        $manager->persist($user);

        $users = [$admin, $user];

        // Création d'autres utilisateurs
        for ($u = 1; $u <= 5; $u++) {
            $newUser = new User();
            $newUser->setEmail($faker->email());
            $newUser->setFirstname($faker->firstName());
            $newUser->setLastname($faker->lastName());
            $newUser->setRoles(["ROLE_USER"]);
            $newUser->setPassword($this->passwordHasher->hashPassword($newUser, "tripbuddy"));
            $manager->persist($newUser);
            $users[] = $newUser;
        }

        $categoryList = [];

        // Boucle 10 fois pour créer 10 catégories
        for ($c = 1; $c <= 10; $c++) {
            $category = new Category();
            $category->setName($faker->word());
            $categoryList[] = $category;
            $manager->persist($category);
        }

        $stepList = [];

        for ($s = 1; $s <= 10; $s++) {
            $step = new Step();
            $step->setName($faker->word());
            $step->setLatitude($faker->randomFloat(6, 0, 80));
            $step->setLongitude($faker->randomFloat(6, 0, 80));
            $step->setDescription($faker->paragraph());
            $stepList[] = $step;

            for ($g = 1; $g <= mt_rand(1, 2); $g++) {
                $step->addCategory($categoryList[mt_rand(0, 9)]);
            }

            $manager->persist($step);
        }

        // On boucle 10 fois pour créer 10 itinéraires
        for ($i = 1; $i <= 10; $i++) {
            $itinerary = new Itinerary();
            $itinerary->setTitle($faker->words(3, true));
            $itinerary->setStartDate(new \DateTimeImmutable($faker->date()));
            $itinerary->setEndDate(new \DateTimeImmutable($faker->date()));

            $randomUser = $users[mt_rand(2, count($users) - 1)]; // Sélectionne un utilisateur aléatoire sauf admin et user
            $itinerary->setUser($randomUser);

            for ($s = 1; $s <= mt_rand(1, 3); $s++) {
                $itinerary->addStep($stepList[mt_rand(0, 9)]);
            }

            $manager->persist($itinerary);
        }

        $manager->flush();
    }
}
