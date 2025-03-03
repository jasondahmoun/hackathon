<?php

namespace App\Controller\Admin;

use App\Entity\Besoin;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class BesoinCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Besoin::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            AssociationField::new('chantier'),
            TextField::new('type_poste'),
            IntegerField::new('nombre_requis'),
        ];
    }
}
