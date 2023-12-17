<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LowestRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currency', ChoiceType::class, [
                'choices'  => [
                    'USD' => 'usd',
                    'EUR' => 'eur',
                ],
                'label' => 'Waluta'
            ])
            ->add('period', ChoiceType::class, [
                'choices'  => [
                    'Tydzień' => 'week',
                    'Miesiąć' => 'month',
                    'Kwartał' => 'quarter',
                ],
                'label' => 'Okres czasu'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Szukaj'
            ])
        ;
    }
}