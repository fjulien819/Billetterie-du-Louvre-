<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/10/2018
 * Time: 10:58
 */

namespace AppBundle\Services\Checkout;


use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Checkout
{

    private $apiKey;
    /**
     * @var Request
     */
    private $request;

    public function __construct($apiKey, RequestStack $requestStack)
    {
        $this->apiKey = $apiKey;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function charge($orderId, $totalPrice, $description)
    {
        Stripe::setApiKey($this->apiKey);
        try {
            $token = $this->request->get('stripeToken');
            Charge::create([
                'amount' => $totalPrice * 100,
                'currency' => 'EUR',
                'description' => $description,
                'source' => $token,
                'metadata' => ['order_id' => $orderId]
            ]);
        } catch (\Exception $e) {
            return false;
        }

        return true;

    }


}