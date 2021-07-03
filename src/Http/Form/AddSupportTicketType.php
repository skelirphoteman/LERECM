<?php

namespace App\Http\Form;

use App\Domain\UserSupport\Entity\SupportTicket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class AddSupportTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Titre de la demande"],
                'label' => false,
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Informations concernant votre demande...", 'rows' => '10'],
                'label' => false,
                'required' => true
            ])
            ->add('type', ChoiceType::class, [
                'attr' => ['class' => 'form-select'],
                'label' => "Objet de la demande : ",
                'choices' => [
                    'Renseignement sur mon abonnement' => 1,
                    'Rapport d\'un bug ' => 2,
                    'Demande d\'amélioration' => 3,
                    'Demande de création d\'un nouveau service sur L.E.R.E.C.M' => 4,
                    'Autre demandes...' => 0,
                ],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SupportTicket::class,
        ]);
    }
}
