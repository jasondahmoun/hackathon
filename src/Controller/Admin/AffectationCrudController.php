<?php 

namespace App\Controller\Admin;

use App\Entity\Affectation;
use App\Entity\Employee;
use App\Entity\Chantier;
use App\Entity\Besoin;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AffectationCrudController extends AbstractCrudController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Affectation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('employe')
                ->setFormTypeOption('choice_label', 'nom')
                ->setLabel('Employé'),
            AssociationField::new('chantier')
                ->setFormTypeOption('choice_label', 'nom')
                ->setLabel('Chantier'),
            DateTimeField::new('start_date')->setLabel('Date de début'),
            DateTimeField::new('end_date')->setLabel('Date de fin'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Affectation $entityInstance */
        $employe = $entityInstance->getEmploye();
        $chantier = $entityInstance->getChantier();
        $startDate = $entityInstance->getStartDate();
        $endDate = $entityInstance->getEndDate();

        // Vérification du rôle de l'employé et mise à jour du besoin
        if (!$this->matchRoleAndUpdateBesoin($employe, $chantier)) {
            throw new \Exception("Le besoin pour ce type de poste a été comblé ou l'employé ne correspond pas au rôle requis.");
        }

        // Vérification de la disponibilité
        if ($this->isEmployeBusy($employe, $startDate, $endDate)) {
            throw new \Exception("L'employé est déjà affecté à un autre chantier pendant cette période.");
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    private function matchRoleAndUpdateBesoin(Employee $employe, Chantier $chantier): bool
    {
        // Récupérer les besoins du chantier
        $besoins = $chantier->getBesoins();

        foreach ($besoins as $besoin) {
            if ($besoin->getTypePoste() === $employe->getRole() && $besoin->getNombreRequis() > 0) {
                // Décrémenter le nombre requis
                $besoin->setNombreRequis($besoin->getNombreRequis() - 1);
                $this->entityManager->persist($besoin);
                $this->entityManager->flush();
                return true;
            }
        }

        return false;
    }

    private function isEmployeBusy(Employee $employe, \DateTime $startDate, \DateTime $endDate): bool
    {
        // Logique pour vérifier si l'employé est déjà affecté à un autre chantier pendant cette période
        foreach ($employe->getAffectations() as $affectation) {
            $affectationStart = $affectation->getStartDate();
            $affectationEnd = $affectation->getEndDate();

            if ($affectationStart <= $endDate && $affectationEnd >= $startDate) {
                return true;
            }
        }

        return false;
    }
}
