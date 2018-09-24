<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderTickets;
use AppBundle\Entity\Ticket;
use AppBundle\Form\OrderTicketsType;
use AppBundle\Form\TicketType;
use AppBundle\Services\Cart\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */

    public function indexAction(Request $request, Cart $cart)
    {


       // $cart->deleteCart();

        $order = new OrderTickets;
        $form = $this->createForm(OrderTicketsType::class, $order);

        $form->handleRequest($request);


        if ($form->isSubmitted()&& $form->isValid())
        {

            $cart->setOrder($order);


            return $this->redirectToRoute("orderPage");

        }


        return $this->render('default/index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/order", name="orderPage")
     */
    public function orderAction(Request $request, Cart $cart)
    {



        if ($cart->getOrder())
        {

            $ticket = new Ticket();

            $ticket->setOrderTickets($cart->getOrder());

            $form = $this->createForm(TicketType::class, $ticket);

            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid())
            {
               // $cart->getOrder()
                ////$cart->addTicket($form->getData());
                $cart->addTicket($ticket);

                if ($cart->fullCart())
               {
                   return $this->redirectToRoute("summaryPage");
               }
            }

            return $this->render('default/order.html.twig', array('form' => $form->createView(),
            ));

            }

        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/summary", name="summaryPage")
     */
    public function summaryAction(Cart $cart)
    {
        dump($cart->getOrder());
        return new Response("page recap");

    }







}