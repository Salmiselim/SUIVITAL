<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            

            ->add('date', TextType::class, [
                'mapped' => false,  // Don't map this field to the entity
                'data' => (new \DateTime())->format('Y-m-d H:i:s'),  // Set the current date
                'attr' => [
                    'readonly' => true,  // Disable editing
                    'class' => 'form-control'
                ],
                'label' => 'Date de Réclamation'
            ])
            ->add('objet', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Objet de la réclamation'
                ],
                'label' => 'Objet',
                'help' => "Veuillez écrire l'objet de votre réclamation.",
                'required' => true,
            ])
            ->add('commentaire', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Décrivez votre réclamation',
                    'rows' => 4
                ],
                'label' => 'Commentaire',
                'help' => "Veuillez remplir le commentaire avec les détails de votre problème.",
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
