<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\TimeSlot;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $service = $options['service'];
        
        $builder
            ->add('timeSlot', EntityType::class, [
                'class' => TimeSlot::class,
                'choice_label' => function (TimeSlot $timeSlot) {
                    return $timeSlot->getStartTime()->format('d/m/Y H:i');
                },
                'query_builder' => function (EntityRepository $er) use ($service) {
                    $qb = $er->createQueryBuilder('t');
                    return $qb
                        ->where('t.isAvailable = :available')
                        ->andWhere('t.startTime > :now')
                        ->setParameter('available', true)
                        ->setParameter('now', new \DateTimeImmutable())
                        ->orderBy('t.startTime', 'ASC');
                },
                'label' => 'Créneau horaire',
                'placeholder' => 'Choisissez un créneau horaire',
                'required' => true
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes ou demandes particulières',
                'required' => false,
                'attr' => ['rows' => 3]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'service' => null
        ]);

        $resolver->setRequired(['service']);
    }
}
