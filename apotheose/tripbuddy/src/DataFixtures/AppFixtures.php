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

    /**
     * Fonction qui va s'executer quand on va charger les fixtures (envoyer les données en bdd)
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //  ! USERS
        $admin = new User();
        $admin->setFirstname($faker->word());
        $admin->setLastname($faker->word());
        $admin->setEmail("admin@oclock.io");
        $admin->setRoles(["ROLE_ADMIN"]);
        // ici j'utilise le passwordhasher pour hasher le mot de passe par rapport à mes infos dans le security.yaml
        // ! SI PAS DE HASH, L'auth ne peut pas marcher
        $admin->setPassword($this->passwordHasher->hashPassword($admin, "admin"));

        $manager->persist($admin);

        $user = new User();
        $user->setEmail("user@oclock.io");
        $user->setFirstname($faker->word());
        $user->setLastname($faker->word());
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->passwordHasher->hashPassword($admin, "user"));

        $manager->persist($user);

        $categoryList = [];

        // Boucle 10 fois pour créer 10 categories
        for ($c = 1; $c <= 10; $c++) {
            // on crée une entité
            $category = new Category();

            $category->setName($faker->word());

            // on l'ajoute à notre tableau $categoryList[]
            $categoryList[] = $category;

            // on persist l'entité
            $manager->persist($category);
        }

        $stepList = [];

        for ($s = 1; $s <= 10; $s++) {
            $step = new Step();
            $step->setName($faker->word());
            $step->setLatitude($faker->randomFloat(6, 0, 80));
            $step->setLongitude($faker->randomFloat(6, 0, 80));
            $step->setDescription(($faker->paragraph()));
            $stepList [] = $step;

            for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                $step->addCategory($categoryList[mt_rand(1, 9)]);
            }

            $manager->persist($step);
        }

        // On boucle 10 fois pour créer 10 itinéraires
        for ($i = 1; $i <= 10; $i++) {

            $itinerary = new Itinerary();
            $itinerary->setTitle($faker->word(3));
            $itinerary->setStartDate(new \DateTimeImmutable($faker->date()));
            $itinerary->setEndDate(new \DateTimeImmutable($faker->date()));

            $itinerary->setUser($user);

            for ($s = 1; $s <= mt_rand(1,3); $s++) {
                $itinerary->addStep($stepList[mt_rand(1,9)]);
              }

            $manager->persist($itinerary);
        }

        $manager->flush();
    }
}