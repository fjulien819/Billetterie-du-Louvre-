<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 16/09/2018
 * Time: 15:09
 */

namespace AppBundle\Services\PriceCalculator;



use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;




class PriceCalculator
{


    private $tarif_enfant;
    private $tarif_normal;
    private $tarif_senior;
    private $tarif_reduit;

    private $age_enfant;
    private $age_adulte;
    private $age_senior;

    private $coef_price_half_day;

    public function __construct($tarif_enfant, $tarif_normal, $tarif_senior, $tarif_reduit, $age_enfant, $age_adulte, $age_senior, $coef_price_half_day)
    {
        $this->tarif_enfant = $tarif_enfant;
        $this->tarif_normal = $tarif_normal;
        $this->tarif_senior = $tarif_senior;
        $this->tarif_reduit = $tarif_reduit;

        $this->age_enfant = $age_enfant;
        $this->age_adulte = $age_adulte;
        $this->age_senior = $age_senior;

        $this->coef_price_half_day = $coef_price_half_day;
    }

    /**
     * @param Ticket $ticket
     */
    public function computeTicketPrice(Ticket $ticket)
    {

        $order = $ticket->getOrderTickets();

        $price = 0;

        if (!$ticket->getReducedPrice())
        {
            $birthDate = $ticket->getBirthDate();
            $visiteDay = $order->getVisiteDay();

            $interval = $birthDate->diff($visiteDay)->format('%y');

                switch (true) {
                    case (($interval >= $this->age_enfant) && ($interval < $this->age_adulte)):
                        $price = $this->tarif_enfant;
                        break;
                    case (($interval >= $this->age_adulte) && ($interval < $this->age_senior)):
                        $price = $this->tarif_normal;
                        break;
                    case ($interval >= $this->age_senior):
                        $price = $this->tarif_senior;
                        break;
                }

        }
        else {
            $price = $this->tarif_reduit;
        }


        if ($order->getTicketType() === Order::TYPE_HALF_DAY)
        {
            $reduction = $price * $this->coef_price_half_day;
            $price = $price - $reduction;
        }

        $ticket->setPrice($price);

    }

    public function computeTotalPrice(Order $order)
    {
        $totalPrice = 0;

        foreach ($order->getTickets() as $ticket)
        {
         $totalPrice += $ticket->getPrice();
        }

        $order->setTotalPrice($totalPrice);
    }
}