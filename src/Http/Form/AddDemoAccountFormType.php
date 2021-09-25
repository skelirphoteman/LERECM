<?php

namespace App\Http\Form;

use App\Domain\DemoAccountForm\Entity\DemoAccountForm;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class AddDemoAccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Nom Prénom"],
                'label' => false,
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Votre adresse mail"],
                'label' => false,
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Votre numéro de téléphone"],
                'label' => false,
                'required' => true
            ])
            ->add('city', EmailType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Ville"],
                'label' => false,
                'required' => true
            ])
            ->add('findy_by', EmailType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Comment avez-vous connu L.E.R.E.C.M ?"],
                'label' => false,
                'required' => false
            ])
            ->add('informations', TextareaType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Informations complémentaires..."],
                'label' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemoAccountForm::class,
        ]);
    }
}
