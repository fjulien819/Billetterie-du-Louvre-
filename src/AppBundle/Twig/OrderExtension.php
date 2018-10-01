<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 30/09/2018
 * Time: 15:42
 */

namespace AppBundle\Twig;

use AppBundle\Services\Cart\Cart;

class OrderExtension extends \Twig_Extension
{
    private $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getFunctions()
    {
        return array(

            new \Twig_SimpleFunction('order', array($this->cart, 'getOrder')),

        );
    }

    public function getName()
    {
        return 'order';
    }


}