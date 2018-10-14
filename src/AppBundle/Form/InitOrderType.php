<?php

namespace AppBundle\Form;

use AppBundle\Entity\Order;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;




class InitOrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('email', EmailType::class, array(
                'required' => true
            ))

            ->add('nbrTickets', IntegerType::class, array(
                'required' => true,
                'attr' => array('min' => Order::MIN_TICKETS_COUNT, "max" => Order::MAX_TICKETS_COUNT),
                'label' => 'Nombre de billets souhaités'
            ))
            ->add('visiteDay', DateType::class, array(
                'required' => true,
                'widget' => "single_text",
                'label' => 'Jour de la visite',

            ))
            ->add('ticketType', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'Billet Journée' => Order::TYPE_FULL_DAY,
                    'Billet Demi-journée' => Order::TYPE_HALF_DAY,
                ),
                'label' => 'Type de billet',
            ))


        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Order::class
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
