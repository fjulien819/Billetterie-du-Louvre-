<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 28/10/2018
 * Time: 17:04
 */

namespace Tests\AppBundle\Validator\Constraints;





use AppBundle\Entity\Order;
use AppBundle\Validator\Constraints\CheckTicketType;
use AppBundle\Validator\Constraints\CheckTicketTypeValidator;

class CheckTicketTypeValidatorTest extends ValidatorTestAbstract
{


    const CURRENT_DATE = '2018-02-01T15:00:00';

    protected function getValidatorInstance()
    {
      return new CheckTicketTypeValidator(self::CURRENT_DATE);
    }

    /**
     * @dataProvider dateForTestOk
     * @param $ticketType
     * @param $visiteDay
     */

    public function testValidationOk($ticketType, $visiteDay)
    {
        $constraint = new CheckTicketType();

        $order = new Order();
        $order->setTicketType($ticketType);
        $order->setVisiteDay(new \DateTime($visiteDay));


        $validator = $this->initValidator();
        $validator->validate($order, $constraint);
    }

    /**
     * @dataProvider dateForTestKo
     * @param $ticketType
     * @param $visiteDay
     */
    public function testValidationKo($ticketType, $visiteDay)
    {
        $constraint = new CheckTicketType();

        $order = new Order();
        $order->setTicketType($ticketType);
        $order->setVisiteDay(new \DateTime($visiteDay));

        $validator = $this->initValidator($constraint->message);
        $validator->validate($order, $constraint);

    }



    public function dateForTestOk()
    {

        return
            [
                [Order::TYPE_FULL_DAY, '2018-02-05']

            ]
        ;
    }

    public function dateForTestKo()
    {
        return
            [
                [Order::TYPE_FULL_DAY, '2018-02-01T15:00:00']

            ]
            ;
    }
}