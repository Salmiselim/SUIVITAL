<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchOrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'required' => false,
                'label' => 'Search by keyword',
                'attr' => ['placeholder' => 'Enter medication, doctor name, etc.'],
            ])
            ->add('doctor', ChoiceType::class, [
                'required' => false,
                'label' => 'Doctor',
                'choices' => $options['doctors'],
                'placeholder' => 'Select a doctor',
            ])
            ->add('dateFrom', DateType::class, [
                'required' => false,
                'label' => 'From Date',
                'widget' => 'single_text',
            ])
            ->add('dateTo', DateType::class, [
                'required' => false,
                'label' => 'To Date',
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'doctors' => [],
        ]);
    }
}
