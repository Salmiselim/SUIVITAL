<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert; // Ajout des contraintes de validation

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Author should not be empty'
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z\s]+$/",
                        'message' => 'Author name must only contain letters'
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Author name should not exceed {{ limit }} characters'
                    ])
                ]
            ])
            ->add('content', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Content should not be empty'
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'minMessage' => 'Content must be at least {{ limit }} characters long'
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s]+$/",
                        'message' => 'Content must not contain special characters'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
