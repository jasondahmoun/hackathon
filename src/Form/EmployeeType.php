<?php

namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('role')
            ->add('disponibilite')
            ->add('chantiers', EntityType::class, [
                'class' => Chantier::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('chantier_actuel', EntityType::class, [
                'class' => Chantier::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
