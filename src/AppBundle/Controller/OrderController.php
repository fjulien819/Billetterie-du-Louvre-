<?php

namespace AppBundle\Controller;

use AppBundle\Form\InitOrderType;
use AppBundle\Form\TicketType;
use AppBundle\Services\Cart\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @throws \AppBundle\Exception\OrderNotFoundException
     */
    public function orderAction(Request $request, Cart $cart)
    {
        $order = $cart->getOrder();

        if ($cart->fullCart($order)) {
            return $this->redirectToRoute("summaryPage");
        }

        $form = $this->createForm(TicketType::class, $cart->generateTicket($order));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cart->addTicket($form->getData(), $order);

            if ($cart->fullCart($order)) {
                return $this->redirectToRoute("summaryPage");
            }else{
                return $this->redirectToRoute("orderPage");
            }
        }
        return $this->render('default/order.html.twig', array('form' => $form->createView(), 'order' => $cart->getOrder()
        ));


    }

    /**
     * @Route("/summary", name="summaryPage")
     * @param Request $request
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\OrderNotFoundException
     */
    public function summaryAction(Request $request, Cart $cart)
    {
        $order = $cart->getOrder();

        if($cart->fullCart($order))
        {

            if ($request->isMethod('POST'))
            {
                if ($cart->payment())
                {
                    return $this->render("default/confirmation.html.twig", array("order" => $order));
                }

                $this->addFlash("checkout", "Le paiement a échoué");

            }

            return $this->render("default/summary.html.twig", array( 'order' => $cart->getOrder()));
        }

        return $this->redirectToRoute('orderPage');
    }


}