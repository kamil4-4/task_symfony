<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CurrencyConversionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currency_first', ChoiceType::class, [
                'choices'  => [
                    'PLN' => 'pln',
                    'USD' => 'usd',
                    'EUR' => 'eur',
                ],
                'label' => 'Waluta kwoty przewalutowywanej'
            ])
            ->add('sum', IntegerType::class)
            ->add('currency_secound', ChoiceType::class, [
                'choices'  => [
                    'PLN' => 'pln',
                    'USD' => 'usd',
                    'EUR' => 'eur',
                ],
                'label' => 'Waluta kwoty po przewalutowaniu'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Przelicz'
            ])
        ;
    }
}