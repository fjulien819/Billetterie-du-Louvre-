<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 18/09/2018
 * Time: 17:50
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckTicketType extends Constraint
{
    public $message = 'Pour une commande le jour même, les billets journées ne sont plus disponible une fois {{ string }} passées';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
