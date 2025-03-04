<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Chantier;
use App\Entity\Employee;
use App\Repository\AffectationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormError;

class AffectationType extends AbstractType
{
    private $affectationRepository;

    public function __construct(AffectationRepository $affectationRepository)
    {
        $this->affectationRepository = $affectationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('employe', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'name', // Utilisez une propriété comme 'name' ou 'id'
            ])
            ->add('chantier', EntityType::class, [
                'class' => Chantier::class,
                'choice_label' => 'id',
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $employeeId = $data['employe'];
        $startDate = new \DateTime($data['start_date']);
        $endDate = new \DateTime($data['end_date']);

        if ($this->affectationRepository->isEmployeeAssigned($employeeId, $startDate, $endDate)) {
            $event->getForm()->addError(new FormError('Cet employé est déjà assigné à un autre chantier pendant cette période.'));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }
}