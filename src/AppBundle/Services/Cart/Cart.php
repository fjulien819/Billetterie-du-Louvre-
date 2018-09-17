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
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $session;
    private $order;
    private $priceCalculator;


    public function __construct(RequestStack $request, PriceCalculator $priceCalculator)
    {
        $this->session = $request->getCurrentRequest()->getSession();
        $this->order = $this->session->get("order");
        $this->priceCalculator = $priceCalculator;
    }

    public function addTicket(Ticket $ticket)
    {

       $nbrTickets = $this->order->getNbrTickets();


        if ($this->isCart())
        {
            $cart = $this->getCart();

            if (count($cart) < $nbrTickets)
            {
                $ticket->setPrice($this->priceCalculator->getTicketPrice($ticket));
                dump(count($cart));
               array_push($cart, $ticket);
               $this->session->set("cart", $cart);

            }

        }
        else
        {
            $cart = array();
            $ticket->setPrice($this->priceCalculator->getTicketPrice($ticket));
            array_push($cart, $ticket);
            $this->session->set("cart", $cart);
        }

    }

    public function isCart()
    {
        return $this->session->has("cart");
    }

    public function getCart()
    {
        if ($this->isCart())
        {
            return $this->session->get("cart");
        }
    }

    public function deleteCart()
    {
        if ($this->isCart())
        {
            $this->session->remove("cart");
        }
    }


}