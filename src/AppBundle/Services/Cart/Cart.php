<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 16/09/2018
 * Time: 10:49
 */

namespace AppBundle\Services\Cart;


use AppBundle\Entity\Ticket;
use AppBundle\Services\PriceCalculator\PriceCalculator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $priceCalculator;


    public function __construct(SessionInterface $session, PriceCalculator $priceCalculator, $email)
    {
        $this->session = $session;
        $this->priceCalculator = $priceCalculator;
    }

    public function addTicket(Ticket $ticket)
    {

        if (!$this->fullCart())
        {
            $ticket->setPrice($this->priceCalculator->getTicketPrice($ticket));
            $this->getOrder()->addTicket($ticket);

        }
        else
        {
            dump("max de tickets");
        }

    }

    public function getOrder()
    {

        if ($this->session->has("order"))
        {
            return $this->session->get("order");
        }
    }

    public function deleteCart()
    {
        if ($this->session->has("order"))
        {
            $this->session->remove("order");
        }

    }

    public function setOrder($order)
    {
        $this->session->set("order", $order);
    }

    public function fullCart()
    {
        $nbrTickets = $this->getOrder()->getNbrTickets();
        if (count($this->getOrder()->getTickets()) < $nbrTickets)
        {
            return false;
        }
        return true;
    }
}