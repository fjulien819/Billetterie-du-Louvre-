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

class Checkout
{

    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    public function charge($token, $totalPrice, $description)
    {
        Stripe::setApiKey($this->apiKey);

       $charge = Charge::create([
            'amount' => $totalPrice * 100,
            'currency' => 'EUR',
            'description' => $description,
            'source' => $token,
        ]);


    }



}