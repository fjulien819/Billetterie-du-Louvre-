<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 11/10/2018
 * Time: 16:17
 */

namespace AppBundle\Exception;


class OrderNotFoundException extends \Exception
{
    protected $message = "Commande non existante";
}