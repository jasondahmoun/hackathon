<?php

namespace App\Controller\Admin;

use App\Entity\Affectation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AffectationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Affectation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            AssociationField::new('employe'),
            AssociationField::new('chantier'),
            DateTimeField::new('date_affectation'),
        ];
    }
}
