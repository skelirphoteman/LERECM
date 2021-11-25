<?php

namespace App\Http\Admin\Form;

use App\Domain\AntiSpamMailer\Entity\AntiSpamMailer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddAntiSpamMailerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', EmailType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Adresse e-mail'],
                'label' => false,
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AntiSpamMailer::class,
        ]);
    }
}
