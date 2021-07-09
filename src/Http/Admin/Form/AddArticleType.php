<?php

namespace App\Http\Admin\Form;

use App\Domain\Article\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
class AddArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Titre'],
                'label' => false,
                'required' => true
            ])
            ->add('state', ChoiceType::class, [
                'attr' => ['class' => 'form-select'],
                'label' => "Etat de l'article : ",
                'choices' => [
                    'En cours d\'écriture ' => 0,
                    'Relecture ' => 1,
                    'Posté ' => 2,
                    'Désactivé ' => 3,
                ],
                'required' => true
            ])
            ->add('content', CKEditorType::class, [
                'required' => true,
            ])
            ->add('created_at', DateTimeType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Date de création'],
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
            'data_class' => Article::class,
        ]);
    }
}
