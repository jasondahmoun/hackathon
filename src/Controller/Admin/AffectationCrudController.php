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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

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
            ->setFormTypeOption('choice_label', function (Employee $employee) {
                return $employee->getNom() . ' (' . $employee->getRole() . ')';
            })
            ->setLabel('Employé'),
            AssociationField::new('chantier')
                ->setFormTypeOption('choice_label', 'nom')
                ->setLabel('Chantier'),
            DateField::new('start_date')->setLabel('Date de début'),
            DateField::new('end_date')->setLabel('Date de fin'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Affectation $entityInstance */
        $employe = $entityInstance->getEmploye();
        $chantier = $entityInstance->getChantier();
        $startDate = $entityInstance->getStartDate();
        $endDate = $entityInstance->getEndDate();
    
        // Vérification que la date de début n'est pas supérieure à la date de fin
        if ($startDate > $endDate) {
            $this->addFlash('danger', 'La date de début ne peut pas être supérieure à la date de fin.');
            return;
        }
    
        // Vérification des dates du chantier
        $chantierStart = $chantier->getDateDebut();
        $chantierEnd = $chantier->getDateFin();
    
        if ($startDate < $chantierStart || $endDate > $chantierEnd) {
            $this->addFlash('danger', 'Les dates de l\'affectation doivent être comprises entre les dates du chantier.');
            return;
        }
    
        // Vérification de la disponibilité
        if ($this->isEmployeBusy($employe, $startDate, $endDate)) {
            $this->addFlash('danger', 'L\'employé est déjà affecté à un autre chantier pendant cette période.');
            return;
        }
    
        // Vérification du besoin
        $besoin = $this->findMatchingBesoin($employe, $chantier);
        if (!$besoin || $besoin->getNombreRequis() <= 0) {
            $this->addFlash('danger', 'Le besoin pour ce type de poste a été comblé ou le rôle ne correspond pas.');
            return;
        }
    
        // Mise à jour du besoin
        $besoin->setNombreRequis($besoin->getNombreRequis() - 1);
        $this->entityManager->persist($besoin);
    
        parent::persistEntity($entityManager, $entityInstance);
    }
    
    private function findMatchingBesoin(Employee $employe, Chantier $chantier): ?Besoin
    {
        foreach ($chantier->getBesoins() as $besoin) {
            if ($besoin->getTypePoste() === $employe->getRole() && $besoin->getNombreRequis() > 0) {
                return $besoin;
            }
        }
        return null;
    }


    private function isEmployeBusy(Employee $employe, \DateTime $startDate, \DateTime $endDate): bool
    {
        foreach ($employe->getAffectations() as $affectation) {
            if ($affectation->getId() === $this->getContext()->getEntity()->getPrimaryKeyValue()) {
                continue; // Ignore l'affectation actuelle en cas d'édition
            }
    
            $existingStart = $affectation->getStartDate();
            $existingEnd = $affectation->getEndDate();
    
            if ($startDate <= $existingEnd && $endDate >= $existingStart) {
                return true;
            }
        }
        return false;
    }
}
