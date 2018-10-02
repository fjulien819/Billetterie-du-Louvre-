<?php

namespace AppBundle\Controller;

use AppBundle\Form\InitOrderType;
use AppBundle\Form\TicketType;
use AppBundle\Services\Cart\Cart;
use AppBundle\Services\PriceCalculator\PriceCalculator;
use Stripe\Charge;
use Stripe\Stripe;
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
     * @param Cart $cart
     * @param PriceCalculator $priceCalculator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function summaryAction(Cart $cart, PriceCalculator $priceCalculator)
    {

        $order = $cart->getOrder();
        $priceCalculator->computeTotalPrice($order);
        return $this->render('default/summary.html.twig', array("order" => $order));

    }

    /**
     * @Route("/checkout", name="order_checkout")
     * @param Request $request
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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