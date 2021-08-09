<?php

namespace App\Http\Form;

use App\Domain\Contract\Entity\Contract;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class AddContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Nom du contrat"],
                'label' => false,
                'required' => true
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Montant du contrat"],
                'label' => false,
                'required' => true
            ])
            ->add('contract_type', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => false,
                'mapped' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Mensuel' => "0",
                    'Trimestriel ' => "1",
                    'Semestriel ' => "2",
                    'Annuel' => "3"
                ],
                'required' => true
            ])
            ->add('start_at', DateTimeType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Date de début (jj/mm/aaaa)'],
                'label' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true
            ])
            ->add('end_at', DateTimeType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Date de fin (jj/mm/aaaa)'],
                'label' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true
            ])
            ->add('next_payment_at', DateTimeType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Premier paiement le (jj/mm/aaaa)'],
                'label' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true
            ])
            ->add('state', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => false,
                'mapped' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Créer ' => "0",
                    'En cours ' => "1",
                    'En Terminé ' => "2",
                    'Résilié ' => "3"
                ],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
