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


    /**
     * @param Ticket $ticket
     */
    public function computeTicketPrice(Ticket $ticket)
    {

        $order = $ticket->getOrderTickets();

        $price = self::TARIF_REDUIT;

        if (!$ticket->getReducedPrice())
        {

            $birthDate = new \DateTime();
            $visiteDay = new \DateTime();

            $birthDate->setTimestamp($ticket->getBirthDate()->getTimestamp());
            $visiteDay->setTimestamp($order->getVisiteDay()->getTimestamp());

            $diff = $visiteDay->diff($birthDate)->format("%y%");

            switch ($diff) {
                case ($diff < self::AGE_ENFANT):
                    $price = 0;
                    break;
                case ($diff >= self::AGE_ENFANT) && ($diff < self::AGE_ADULTE):
                    $price = self::TARIF_ENFANT;
                    break;
                case ($diff >= self::AGE_ADULTE) && ($diff < self::AGE_SENIOR):
                    $price = self::TARIF_NORMAL;
                    break;
                case ($diff >= self::AGE_SENIOR):
                    $price = self::TARIF_SENIOR;
                    break;
            }


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