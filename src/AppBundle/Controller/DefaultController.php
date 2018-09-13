<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderTickets;
use AppBundle\Form\OrderTicketsType;
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
    public function billetAction(Request $request)
    {
        $session = $request->getSession();

        if ($session->has("order"))
        {
            dump($session->get("order"));

            return new Response(
                '<html><body>Page Order</body></html>'
            );
        }


        return $this->redirectToRoute("homepage");
    }

}