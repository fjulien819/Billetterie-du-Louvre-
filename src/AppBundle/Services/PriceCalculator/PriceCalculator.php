<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 16/09/2018
 * Time: 15:09
 */

namespace AppBundle\Services\PriceCalculator;



use AppBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\Session\Session;


class PriceCalculator
{
    private $order;

    const TARIF_ENFANT = "8";
    const TARIF_NORMAL = "16";
    const TARIF_SENIOR = "12";
    const TARIF_REDUIT = "10";

    public function __construct(Session $session)
    {
        $this->order = $session->get("order");
    }

    public function getTicketPrice(Ticket $ticket)
    {

        $price = self::TARIF_REDUIT;

        if (!$ticket->getReducedPrice())
        {

            $birthDate = new \DateTime();
            $visiteDay = new \DateTime();

            $birthDate->setTimestamp($ticket->getBirthDate()->getTimestamp());
            $visiteDay->setTimestamp($this->order->getVisiteDay()->getTimestamp());

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