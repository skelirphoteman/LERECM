<?php

namespace App\Http\Form;

use App\Domain\Documents\Entity\Quote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class AddQuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename', FileType::class, [
                'label' => false,

                'attr' => ["class" => "form-control"],

                'mapped' => false,

                'required' => true,

                'constraints' => [
                    new File([
                        'maxSize' => '10000M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez déposer un fichier PDF',
                    ])
                ],
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Nom du devis"],
                'label' => false,
                'required' => true
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Montant du devis"],
                'label' => false,
                'required' => false
            ])
            ->add('state', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => false,
                'mapped' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'En attente de signature ' => "0",
                    'Signé' => "1"
                ],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
        ]);
    }
}
