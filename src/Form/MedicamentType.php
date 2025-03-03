<?php

namespace App\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use App\Entity\Medicament;

    class MedicamentType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('name', TextType::class, [
                    'attr' => ['class' => 'form-control'],
                ])  
                ->add('dosage', TextType::class, [
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('duration', IntegerType::class, [
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('frequency', TextType::class, [
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Save Medicament',
                    'attr' => ['class' => 'btn btn-primary mt-3'],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Medicament::class,
            ]);
        }
    }