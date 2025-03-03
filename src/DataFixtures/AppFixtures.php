<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Employee;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Créer l'utilisateur admin
        $admin = $this->createUser('admin@example.com', 'pass_1234', ['ROLE_ADMIN']);
        $manager->persist($admin);

        // Créer 10 employés
        $employees = [];
        for ($i = 0; $i < 10; $i++) {
            $employee = new Employee();
            $employee->setNom($faker->lastName);
            $employee->setPrenom($faker->firstName);
            $employee->setEmail($faker->email);
            $employee->setRole('Employé');
            $employee->setDisponibilite(true);
            $manager->persist($employee);
            $employees[] = $employee;
        }

        // Créer 10 utilisateurs et les associer aux employés
        for ($i = 0; $i < 10; $i++) {
            $user = $this->createUser($faker->email, 'pass_1234', ['ROLE_USER']);
            $user->setEmploye($employees[$i]);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createUser(string $email, string $plainPassword, array $roles): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);

        $password = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($password);

        return $user;
    }
}
