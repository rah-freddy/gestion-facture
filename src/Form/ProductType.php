<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité',
                'html5' => true,
                'attr' => [
                    'inputmode' => 'numeric',
                    'pattern' => '[0-9]*'
                ]
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Montant (€)',
                'scale' => 2,
                'html5' => true,
                'attr' => [
                    'inputmode' => 'decimal',
                    'pattern' => '[0-9]*',
                ],
            ])
            ->add('amountTVA', NumberType::class, [
                'label' => 'Montant de la TVA ',
                'scale' => 2,
                'attr' => [
                    'inputmode' => 'decimal',
                ],
                'disabled' => true,
            ])
            ->add('totalTVA', NumberType::class, [
                'label' => 'Total avec TVA',
                'scale' => 2,
                'attr' => [
                    'inputmode' => 'decimal',
                ],
                'disabled' => true,
            ])
            ->add('invoice', EntityType::class, [
                'class' => Invoice::class,
                'label' => 'Facture',
                'placeholder' => 'Choisissez le facture',
                'choice_label' => function (Invoice $invoice) {
                    return $invoice->getId();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
