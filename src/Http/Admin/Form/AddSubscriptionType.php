<?php

namespace App\Http\Admin\Form;

use App\Domain\Subscription\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subscription_access', ChoiceType::class, [
                'label_attr' => ['class' => 'checkbox-custom'],
                'label' => "Type de client : ",
                'mapped' => true,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Par default ' => "DEFAULT"
                ],
                'required' => true
            ])
            ->add('end_at', DateTimeType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Date d\'anniversaire jj/mm/aaaa'],
                'label' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}
