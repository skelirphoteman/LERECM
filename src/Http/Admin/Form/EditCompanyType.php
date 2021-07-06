<?php

namespace App\Http\Admin\Form;

use App\Domain\Company\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Nom de l'entreprise"],
                'label' => false,
                'required' => true
            ])
            ->add('siret_code', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "N° de Siret"],
                'label' => false,
                'required' => false
            ])
            ->add('ape_code', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Code Ape"],
                'label' => false,
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "N° de tel."],
                'label' => false,
                'required' => false
            ])
            ->add('avenue', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Adresse"],
                'label' => false,
                'required' => false
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Ville"],
                'label' => false,
                'required' => false
            ])
            ->add('tva_code', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "N° de TVA"],
                'label' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
