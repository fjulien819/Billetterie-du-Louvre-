<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 17/10/2018
 * Time: 15:25
 */

namespace Tests\AppBundle\Services;


use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Services\PriceCalculator\PriceCalculator;
use PHPUnit\Framework\TestCase;



class PriceCalculatorTest extends TestCase
{
    /**
     * @param $birthDate
     * @param $visiteDay
     * @param $expectedPrice
     * @param $reducedPrice
     * @dataProvider dateForComputeTicketPrice
     */
    public function testcomputeTicketPrice($birthDate, $visiteDay ,$expectedPrice, $reducedPrice)
    {
        $ticket = new Ticket();
        $birthDate = new \DateTime($birthDate);
        $ticket->setReducedPrice($reducedPrice);
        $ticket->setBirthDate($birthDate);


        $order = new Order();
        $visiteDay = new \DateTime($visiteDay);
        $order->setVisiteDay($visiteDay);

        $ticket->setOrderTickets($order);

        $priceCalculator = new PriceCalculator(8, 16, 12, 10, 4, 12, 60, 0.5 );

        $priceCalculator->computeTicketPrice($ticket);
        $result = $ticket->getPrice();

        $this->assertSame($expectedPrice, $result);
    }

    /**
     * @param $ticketsPrice
     * @param $expectedPrice
     * @dataProvider pricesForComputeTotalPrice
     */
    public function testcomputeTotalPrice($ticketsPrice, $expectedPrice)
    {

        $order = new Order();

        foreach ($ticketsPrice as $price)
        {
           $ticket = new Ticket();
           $ticket->setPrice($price);
           $order->addTicket($ticket);
        }



        $priceCalculator = new PriceCalculator(8, 16, 12, 10, 4, 12, 60, 0.5 );

        $priceCalculator->computeTotalPrice($order);
        $result = $order->getTotalPrice();

        $this->assertSame($expectedPrice, $result);

    }


    public function  pricesForComputeTotalPrice()
    {
        return [
            [["8", "16"], 24],
            [["16", "16"], 32],
            [["12", "16"], 28],
            [["10", "8"], 18]
        ];
    }

    public function  dateForComputeTicketPrice()
    {
        return [
            ["2000-01-01", "2018-01-01", 16, false],
            ["2014-01-01", "2018-01-01", 8, false],
            ["1900-01-01", "2018-01-01", 12, false],
            ["1900-01-01", "2018-01-01", 10, true]
        ];
    }



}
