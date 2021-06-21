<?php

namespace App\Http\Admin\Form;

use App\Domain\User\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddUserType extends AbstractType
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
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Adresse e-mail'],
                'label' => false,
                'required' => true
            ])
            ->add('roles', ChoiceType::class, [
                'label_attr' => ['class' => 'checkbox-custom'],
                'label' => "Type de client : ",
                'mapped' => true,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Admin ' => "ROLE_ADMIN",
                    'Entreprise ' => "ROLE_COMPANY"
                ],
                'required' => true
            ])
            ->add('created_at', DateTimeType::class, [
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
            'data_class' => User::class,
        ]);
    }
}
