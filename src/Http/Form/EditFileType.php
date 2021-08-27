<?php

namespace App\Http\Form;

use App\Domain\Contract\Entity\Contract;
use App\Domain\Contract\Repository\ContractRepository;
use App\Domain\Documents\Entity\File as Doc;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class EditFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $client_id = $options['client_id'];


        $builder
            ->add('filename', FileType::class, [
                'label' => false,

                'attr' => ["class" => "form-control"],

                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez déposer un fichier PDF',
                    ])
                ],
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Nom du document"],
                'label' => false,
                'required' => true
            ])
            ->add('contract', EntityType::class, [
                'class' => Contract::class,
                'query_builder' => function(ContractRepository $contractRepository) use ($client_id){
                    return $contractRepository->createQueryBuilder('c')
                        ->where('c.client = :id')
                        ->setParameter('id', $client_id)
                        ;
                },
                'choice_label' => function(Contract $contract) {
                    return sprintf('(%d) %s', $contract->getId(), $contract->getTitle());
                },
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => "Joindre cette facture à un contrat"

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Doc::class,
            'client_id' => null,
        ]);
    }
}
