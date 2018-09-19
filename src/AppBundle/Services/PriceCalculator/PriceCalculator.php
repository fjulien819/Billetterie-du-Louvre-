<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 16/09/2018
 * Time: 15:09
 */

namespace AppBundle\Services\PriceCalculator;



use AppBundle\Entity\OrderTickets;
use AppBundle\Entity\Ticket;




class PriceCalculator
{


    const TARIF_ENFANT = "8";
    const TARIF_NORMAL = "16";
    const TARIF_SENIOR = "12";
    const TARIF_REDUIT = "10";


    /*

    public function computeOrderPrice(OrderTickets $order)
    {
        foreach($order-W>getTickets() as $ticket){
            $totalPrice += $this->getTicketPrice($ticket);
        }

    }

    */
    public function getTicketPrice(Ticket $ticket)
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
                case ($diff < 4):
                    $price = 0;
                    break;
                case ($diff >= 4) && ($diff < 12) :
                    $price = self::TARIF_ENFANT;
                    break;
                case ($diff >= 12) && ($diff < 60):
                    $price = self::TARIF_NORMAL;
                    break;
                case ($diff >= 60):
                    $price = self::TARIF_SENIOR;
                    break;
            }


        }

        return $price;

    }
}