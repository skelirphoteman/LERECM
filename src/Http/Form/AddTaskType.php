<?php

namespace App\Http\Form;

use App\Domain\Task\Entity\Task;
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


class AddTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Nom de la tâche"],
                'label' => false,
                'required' => true
            ])
            ->add('end_at', DateTimeType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Date de fin jj/mm/aaaa'],
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
                    'En cours ' => "0",
                    'Terminé ' => "1"
                ],
                'required' => true
            ])

            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Informations complémentaires'],
                'label' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
