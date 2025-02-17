<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Ordonnance;
use Symfony\Component\Form\AbstractType;
use App\Entity\Medicament;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Patient;
use App\Entity\Doctor;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('medicaments', EntityType::class, [
                'class' => Medicament::class,
                'choice_label' => 'name',
                'multiple' => true,  
                'expanded' => false, 
                'label' => 'Medications',
                'by_reference' => false,
                'attr' => ['class' => 'form-control scrollable-multiselect'], 
            ])
            
            
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'name',
                'label' => 'Patient',
                'attr' => ['class' => 'form-control'],
            ])
           
            ->add('save', SubmitType::class, [
                'label' => 'enregistrer Ordonnance',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
            'csrf_protection' => true,
        ]);
    }
}