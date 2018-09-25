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


        $form = $this->createForm(OrderTicketsType::class);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $cart->setOrder($form->getData());


            return $this->redirectToRoute("orderPage");

        }


        return $this->render('default/index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/order", name="orderPage")
     */
    public function orderAction(Request $request, Cart $cart)
    {
        $form = $this->createForm(TicketType::class, $cart->generateTicket());

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // $cart->getOrder()
            ////$cart->addTicket($form->getData());
            ///

            $cart->addTicket($form->getData());

            if ($cart->fullCart()) {
                return $this->redirectToRoute("summaryPage");
            }else{
                return $this->redirectToRoute('orderPage');
            }
        }

        return $this->render('default/order.html.twig', array('form' => $form->createView(),
        ));

    }

    /**
     * @Route("/summary", name="summaryPage")
     */
    public function summaryAction(Cart $cart)
    {

        $order = $cart->getOrder();


        return $this->render('default/summary.html.twig', array("order" => $order));

    }


}