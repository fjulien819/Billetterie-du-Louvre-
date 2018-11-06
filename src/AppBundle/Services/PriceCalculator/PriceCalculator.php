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


    const TARIF_ENFANT = "8";
    const TARIF_NORMAL = "16";
    const TARIF_SENIOR = "12";
    const TARIF_REDUIT = "10";

    const AGE_ENFANT = 4;
    const AGE_ADULTE = 12;
    const AGE_SENIOR = 60;

    const COEF_PRICE_HALF_DAY = 0.5;


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
                    case (($interval >= self::AGE_ENFANT) && ($interval < self::AGE_ADULTE)):
                        $price = self::TARIF_ENFANT;
                        break;
                    case (($interval >= self::AGE_ADULTE) && ($interval < self::AGE_SENIOR)):
                        $price = self::TARIF_NORMAL;
                        break;
                    case ($interval >= self::AGE_SENIOR):
                        $price = self::TARIF_SENIOR;
                        break;
                }

        }
        else {
            $price = self::TARIF_REDUIT;
        }


        if ($order->getTicketType() === Order::TYPE_HALF_DAY)
        {
            $reduction = $price * self::COEF_PRICE_HALF_DAY;
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