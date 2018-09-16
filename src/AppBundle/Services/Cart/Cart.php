<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 16/09/2018
 * Time: 10:49
 */

namespace AppBundle\Services\Cart;


use AppBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $session;
    private $order;


    public function __construct(RequestStack $request)
    {
        $this->session = $request->getCurrentRequest()->getSession();
        $this->order = $this->session->get("order");

    }

    public function addTicket(Ticket $ticket)
    {

       $nbrTickets = $this->order->getNbrTickets();

        if ($this->isCart())
        {
            $cart = $this->getCart();

            if (count($cart) < $nbrTickets)
            {
                dump(count($cart));
               array_push($cart, $ticket);
               $this->session->set("cart", $cart);

            }

        }
        else
        {
            $cart = array();
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