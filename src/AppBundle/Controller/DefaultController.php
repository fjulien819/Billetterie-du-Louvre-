<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderTickets;
use AppBundle\Entity\Ticket;
use AppBundle\Form\OrderTicketsType;
use AppBundle\Form\TicketType;
use AppBundle\Services\Cart\Cart;
use AppBundle\Services\PriceCalculator\PriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Charge;

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
    public function summaryAction(Cart $cart, PriceCalculator $priceCalculator)
    {

        $order = $cart->getOrder();
        $priceCalculator->computeTotalPrice($order);
        return $this->render('default/summary.html.twig', array("order" => $order));

    }

    /**
     * @Route("/checkout", name="order_checkout")
     */
    public function checkoutAction(Request $request, Cart $cart)
    {


        Stripe::setApiKey("sk_test_PvRPdtQtuQZNAhoJeQvKg65o");
        $token =  $request->get('stripeToken');
        $email = $request->get('stripeEmail');
        $totalPrice = $cart->getOrder()->getTotalPrice() * 100;

        try
        {
            $charge = Charge::create([
                'amount' => $totalPrice,
                'currency' => 'EUR',
                'description' => 'Billetterie du Louvre',
                'source' => $token,
            ]);

            $this->addFlash("checkout", "Commande validée, vous allez recevoir les billets par mail.");
            return $this->redirectToRoute("homepage");
        }
        catch(\Exception $e) {


        }

        $this->addFlash("checkout", "Le paiement a échoué");
        return $this->redirectToRoute("summaryPage");

    }

}