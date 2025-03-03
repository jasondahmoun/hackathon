<?php
namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Employee;
use App\Entity\Chantier;
use App\Entity\Affectation;
use App\Entity\Besoin;
use App\Entity\Competence;
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

        // Liste des types de poste prédéfinis
        $typesDePoste = ['Maçon', 'Électricien', 'Plombier', 'Chauffagiste'];

        // Compétences associées à chaque type de poste
        $competencesParType = [
            'Maçon' => ['Construction de murs', 'Pose de carrelage', 'Réparation de façades'],
            'Électricien' => ['Installation électrique', 'Dépannage électrique', 'Mise en conformité'],
            'Plombier' => ['Installation sanitaire', 'Réparation de fuites', 'Pose de chauffe-eau'],
            'Chauffagiste' => ['Installation de chaudières', 'Entretien de chauffage', 'Dépannage de chauffage'],
        ];

        // Créer l'utilisateur admin
        $admin = $this->createUser('admin@example.com', 'pass_1234', ['ROLE_ADMIN']);
        $manager->persist($admin);

        // Créer des compétences pour chaque type de poste
        $competences = [];
        foreach ($competencesParType as $type => $competenceNames) {
            foreach ($competenceNames as $name) {
                $competence = new Competence();
                $competence->setNom($name);
                $manager->persist($competence);
                $competences[$type][] = $competence;
            }
        }

        // Créer 5 chantiers
        $chantiers = [];
        for ($i = 0; $i < 5; $i++) {
            $chantier = new Chantier();
            $chantier->setNom($faker->company);
            $chantier->setAdresse($faker->address);
            $chantier->setDateDebut($faker->dateTimeBetween('-1 year', 'now'));
            $chantier->setDateFin($faker->dateTimeBetween('now', '+1 year'));
            $manager->persist($chantier);
            $chantiers[] = $chantier;

            // Créer des besoins pour chaque chantier
            for ($j = 0; $j < 3; $j++) {
                $typePoste = $faker->randomElement($typesDePoste);
                $besoin = new Besoin();
                $besoin->setChantier($chantier);
                $besoin->setTypePoste($typePoste);
                $besoin->setNombreRequis($faker->numberBetween(1, 10));
                $manager->persist($besoin);
                $chantier->addBesoin($besoin);
            }
        }

        // Créer 10 employés
        $employees = [];
        for ($i = 0; $i < 10; $i++) {
            $typePoste = $faker->randomElement($typesDePoste);
            $employee = new Employee();
            $employee->setNom($faker->lastName);
            $employee->setPrenom($faker->firstName);
            $employee->setEmail($faker->email);
            $employee->setRole($typePoste); // Assigner le poste comme rôle
            $employee->setDisponibilite(true);
            $manager->persist($employee);
            $employees[] = $employee;

            // Associer des compétences en fonction du poste
            $randomCompetences = $faker->randomElements($competences[$typePoste], $faker->numberBetween(1, 3));
            foreach ($randomCompetences as $competence) {
                $employee->addCompetence($competence);
            }
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

