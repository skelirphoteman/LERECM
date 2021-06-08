<?php

namespace App\Http\Form;

use App\Domain\Client\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Prénom'],
                'label' => false,
                'required' => false
            ])
            ->add('surname', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom de famille'],
                'required' => true,
                'label' => false
            ])
            ->add('phone', TextType::class, [
                'attr' => ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => 'Téléphone mobile'],
                'label' => false,
                'required' => false
            ])
            ->add('home_phone', TextType::class, [
                'attr' => ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => 'Téléphone fixe'],
                'label' => false,
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Adresse e-mail'],
                'label' => false,
                'required' => false
            ])
            ->add('avenue', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Voie'],
                'label' => false,
                'required' => false
            ])
            ->add('postal_code', IntegerType::class, [
                'attr' => ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => 'Code Postal'],
                'label' => false,
                'required' => false
            ] )
            ->add('city',TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ville'],
                'label' => false,
                'required' => false
            ])
            ->add('country', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Pays'],
                'label' => false,
                'required' => false
            ])
            ->add('is_company', ChoiceType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'choice_type'],
                'label' => "Type de client : ",
                'mapped' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Entreprise ' => "0",
                    'Particulié ' => "1"
                ],
                'required' => true
            ])
            ->add('company_name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom de l\'entreprise'],
                'label' => false,
                'required' => false
            ])
            ->add('siret', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'N° de Siret'],
                'label' => false,
                'required' => false
            ])
            ->add('ape_code', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Code APE'],
                'label' => false,
                'required' => false
            ])
            ->add('tva_code', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'N° de TVA'],
                'label' => false,
                'required' => false
            ])
            ->add('note', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => false,
                'required' => false
            ])
            ->add('birthday', DateTimeType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Date d\'anniversaire jj/mm/aaaa'],
                'label' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
