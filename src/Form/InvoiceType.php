<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Invoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('billDate', DateType::class, [
                'label' => 'Date de facture',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('billNumber', NumberType::class, [
                'label' => 'Numéro de facture',
                'html5' => true,
                'attr' => [
                    'inputmode' => 'numeric',
                    'pattern' => '[0-9]*'
                ]
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'label' => 'Client',
                'placeholder' => 'Choisissez le client',
                'choice_label' => function (Customer $customer) {
                    return $customer->getId();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
