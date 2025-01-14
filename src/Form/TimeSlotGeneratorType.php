<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class TimeSlotGeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(['value' => 'today'])
                ]
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(['value' => 'today'])
                ]
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Heure de début',
                'widget' => 'single_text',
                'constraints' => [new NotBlank()]
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'Heure de fin',
                'widget' => 'single_text',
                'constraints' => [new NotBlank()]
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée des créneaux (minutes)',
                'attr' => [
                    'min' => 15,
                    'max' => 120,
                    'step' => 15
                ],
                'constraints' => [
                    new NotBlank(),
                    new Range([
                        'min' => 15,
                        'max' => 120,
                        'minMessage' => 'La durée minimum est de {{ limit }} minutes',
                        'maxMessage' => 'La durée maximum est de {{ limit }} minutes'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
