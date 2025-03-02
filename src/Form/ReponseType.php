<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_token', HiddenType::class, [
                'mapped' => false,
                'data' => $options['csrf_token_id'] ?? 'reponse',
            ])
            ->add('date', TextType::class, [
                'mapped' => false,  // Don't map this field to the entity
                'data' => $options['data']->getDate()->format('Y-m-d H:i:s'),  // Display the current date as text
                'attr' => [
                    'readonly' => true,  // Make the field readonly
                    'class' => 'form-control-plaintext',  // Optional: For Bootstrap styling of readonly field
                ],
            ])
            ->add('objet', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'objet ne peut pas être vide.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'L\'objet ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Objet de la réponse']
            ])
            ->add('commentaire', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le commentaire ne peut pas être vide.']),
                ],
                'attr' => ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Votre commentaire ici']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
