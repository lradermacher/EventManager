<?php

namespace App\Form\Type;

use App\Entity\Event;
use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class TicketType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('barcode', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Length(min: 1, max: 8),
                    new Type('alnum'),
                ]
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotNull()
                ]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotNull()
                ]
            ])
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'constraints' => [
                    new NotNull()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'csrf_protection' => false,
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => Ticket::class,
                    'fields' => ['event', 'barcode'],
                ]),
            ]
        ]);
    }
}