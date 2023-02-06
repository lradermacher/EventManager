<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Entity\Event;

class EventType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotNull()
                ]
            ])
            ->add('date', DateTimeType::class, [
                "widget" => 'single_text',
                "data" => new \DateTime(),
                'constraints' => [
                    new NotNull()
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotNull()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'csrf_protection' => false,
        ]);
    }
}