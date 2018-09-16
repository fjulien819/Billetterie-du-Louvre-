<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderTickets;
use AppBundle\Entity\Ticket;
use AppBundle\Form\OrderTicketsType;
use AppBundle\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */

    public function indexAction(Request $request)
    {

        $order = new OrderTickets;
        $form = $this->createForm(OrderTicketsType::class, $order);

        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid())
        {

            $session = $request->getSession();
            $session->set("order", $order);


            return $this->redirectToRoute("orderPage");
        }


        return $this->render('default/index.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/order", name="orderPage")
     */
    public function orderAction(Request $request)
    {
        $session = $request->getSession();

        if ($session->has("order"))
        {

            $ticket = new Ticket();
            $ticket->setOrderTickets($session->get("order"));

            $form = $this->createForm(TicketType::class, $ticket);

            $form->handleRequest($request);


            if ($request->isMethod("POST") && $form->isValid())
            {


                if ($session->has("tickets"))
                {

                   $tickets = $session->get("tickets");
                   array_push($tickets, $ticket );


                }

                else
                    {

                        $tickets = array();
                        array_push($tickets, $ticket);
                }

                $session->set("tickets", $tickets);





            }


dump($session->get("tickets"));

            return $this->render('default/index.html.twig', array('form' => $form->createView(),
            ));


            /*
            return new Response(
                '<html><body>Page Order</body></html>'
            );
            */
        }





        return $this->redirectToRoute("homepage");
    }

}