<?php

namespace App\Form;

use App\Entity\Ordonnance;
use App\Entity\Patient;
use App\Entity\Doctor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datePrescription', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('patientId', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'name', 
                'label' => 'Patient',
            ])
            ->add('doctorId', EntityType::class, [
                'class' => Doctor::class,
                'choice_label' => 'name', 
                'label' => 'Doctor',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}