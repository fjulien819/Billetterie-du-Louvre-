<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class OrderTicketsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbrTickets', IntegerType::class, array(
                'required' => true,
                'attr' => array('min' => 0),
                'label' => 'Nombre de billets souhaités',
            ))
            ->add('visiteDay', DateType::class, array(
                'required' => true,
                'widget' => "choice",
                'label' => 'Jour de la visite',

            ))
            ->add('ticketType', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'Billet Journée' => "journee",
                    'Billet Demi-journée' => "demiJournee",
                ),
                'label' => 'Type de billet',
            ))
            ->add("save", submitType::class, array(
                'label' => 'Valider',
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\OrderTickets'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ordertickets';
    }


}
