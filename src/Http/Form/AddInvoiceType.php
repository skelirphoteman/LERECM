<?php

namespace App\Http\Form;

use App\Domain\Client\Entity\Client;
use App\Domain\Contract\Entity\Contract;
use App\Domain\Contract\Repository\ContractRepository;
use App\Domain\Documents\Entity\Invoice;
use App\Domain\Documents\Repository\InvoiceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class AddInvoiceType extends AbstractType
{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $client_id = $options['client_id'];

        $builder
            ->add('filename', FileType::class, [
                'label' => false,

                'attr' => ["class" => "form-control"],

                'mapped' => false,

                'required' => true,

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
                'attr' => ['class' => 'form-control', "placeholder" => "Nom de la facture"],
                'label' => false,
                'required' => true
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => 'form-control', "placeholder" => "Montant de la facture"],
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
                    'En attente de paiement ' => "0",
                    'Payé ' => "1"
                ],
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
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
            'client_id' => null,
        ]);
    }
}
