<?php

namespace App\Http\Form;

use App\Domain\Intervention\Entity\Intervention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AddInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_visible', CheckboxType::class, [
                'label'    => 'Voulez-vous que le client vois cette intervention sur son compte ?',
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Nom de l'intervention"],
                'label' => false,
                'required' => true
            ])
            ->add('informations', TextareaType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Informations sur l'intervention"],
                'label' => false,
                'required' => true
            ])
            ->add('start_at', DateTimeType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => "Début de l'intervention :",
                'html5' => false,
                'input' => 'datetime_immutable',
                'format' => 'dd/MM/yyyy HH:mm',
                'required' => true
            ])
            ->add('end_at', DateTimeType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => "Fin de l'intervention :",
                'html5' => false,
                'input' => 'datetime_immutable',
                'format' => 'dd/MM/yyyy HH:mm',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
