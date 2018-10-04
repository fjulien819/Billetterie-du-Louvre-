<?php

namespace AppBundle\Controller;

use AppBundle\Form\InitOrderType;
use AppBundle\Form\TicketType;
use AppBundle\Services\Cart\Cart;
use AppBundle\Services\PriceCalculator\PriceCalculator;
use AppBundle\Services\SendEmail\SendEmail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */

    public function indexAction(Request $request, Cart $cart)
    {


        $form = $this->createForm(InitOrderType::class);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $cart->setOrder($form->getData());
            return $this->redirectToRoute("orderPage");

        }


        return $this->render('default/index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/order", name="orderPage")
     * @param Request $request
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function orderAction(Request $request, Cart $cart)
    {
        if ($cart->fullCart()) {
            return $this->redirectToRoute("summaryPage");
        }
        $form = $this->createForm(TicketType::class, $cart->generateTicket());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cart->addTicket($form->getData());

            return $this->redirectToRoute('orderPage');
        }
        return $this->render('default/order.html.twig', array('form' => $form->createView(), 'order' => $cart->getOrder()
        ));

    }

    /**
     * @Route("/summary", name="summaryPage")
     * @param Cart $cart
     * @param PriceCalculator $priceCalculator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function summaryAction(Request $request, Cart $cart, PriceCalculator $priceCalculator, SendEmail $email)
    {

        $order = $cart->getOrder();
        $priceCalculator->computeTotalPrice($order);

        if ($request->isMethod('POST'))
        {
            $token =  $request->get('stripeToken');

            $payment = $cart->payment($token,"Billetterie du Louvre");

            if ($payment === true)
            {
                $email->sendTicket($order);
                $cart->deleteCart();
                return $this->render("default/confirmation.html.twig", array("order" => $order));
            }

            $this->addFlash("checkout", "Le paiement a échoué");
            return $this->redirectToRoute("summaryPage");


        }

        return $this->render("default/summary.html.twig", array( 'order' => $cart->getOrder()));
    }


}