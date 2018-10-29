<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 23/10/2018
 * Time: 13:48
 */

namespace Tests\AppBundle\Services;


use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Services\Cart\Cart;
use AppBundle\Services\Checkout\Checkout;
use AppBundle\Services\PriceCalculator\PriceCalculator;
use AppBundle\Services\SendEmail\SendEmail;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartTest extends TestCase
{
    private $sessionMock;
    private $priceCalculatorMock;
    private $checkoutMock;
    private $emMock;
    private $sendEmailMock;
    private $cartMock;
    private $cart;


    public function setUp()
    {

        $this->sessionMock = $session = new Session(new MockArraySessionStorage());

        $this->priceCalculatorMock = $this
            ->getMockBuilder(PriceCalculator::class)
            ->disableOriginalConstructor()
            ->setMethods(['computeTicketPrice', 'computeTotalPrice'])
            ->getMock();

        $this->checkoutMock = $this
            ->getMockBuilder(Checkout::class)
            ->disableOriginalConstructor()
            ->setMethods(['charge'])
            ->getMock();

        $this->emMock = $this
            ->createMock(EntityManagerInterface::class);

        $this->sendEmailMock = $this
            ->getMockBuilder(SendEmail::class)
            ->disableOriginalConstructor()
            ->setMethods(['sendTicket'])
            ->getMock();

        $this->cart = new Cart($this->sessionMock, $this->priceCalculatorMock, $this->checkoutMock, $this->emMock, $this->sendEmailMock);

        $this->cartMock = $this->getMockBuilder(Cart::class)
            ->setConstructorArgs([$this->sessionMock, $this->priceCalculatorMock, $this->checkoutMock, $this->emMock, $this->sendEmailMock])
            ->setMethodsExcept(['payment'])
            ->setMethods(['getOrder'])
            ->getMock();


    }


    public function testAddTicket()
    {
        $order = new Order();
        $order->setNbrTickets(2);

        $ticket = new Ticket();

        $this->priceCalculatorMock
            ->method('computeTicketPrice')
            ->willReturn($ticket->setPrice(10));

        $this->priceCalculatorMock
            ->method('computeTotalPrice')
            ->willReturn($order->setTotalPrice(10));

        $this->cart->addTicket($ticket, $order);

        $this->assertAttributeCount(1, 'tickets', $order);

    }

    public function testGetOrder()
    {
        $order = new Order();
        $this->sessionMock->set(Cart::SESSION_ORDER_KEY, $order);

        $this->assertSame($order, $this->cart->getOrder());
    }

    public function testDeleteCart()
    {

        $order = new Order();
        $this->sessionMock->set(Cart::SESSION_ORDER_KEY, $order);
        $this->cart->deleteCart();

        $this->assertSame(false, $this->sessionMock->has(Cart::SESSION_ORDER_KEY));

    }

    public function testSetOrder()
    {

        $order = new Order();
        $this->cart->setOrder($order);

        $this->assertSame(true, $this->sessionMock->has(Cart::SESSION_ORDER_KEY));

    }


    public function testGenerateIdOrder()
    {

        $idOrder = $this->cart->generateIdOrder();
        $result = preg_match('/([A-Za-z0-9]{' . Cart::ORDER_ID_LENGTH . '})/', $idOrder);
        $this->assertSame(1, $result);
    }


    public function testNotFullCart()
    {

        $order = new Order();
        $order->setNbrTickets(2);
        $this->assertSame(false, $this->cart->fullCart($order));

    }

    public function testFullCart()
    {

        $order = new Order();
        $order->setNbrTickets(0);
        $this->assertSame(true, $this->cart->fullCart($order));

    }

    public function testGenerateTicket()
    {
        $order = new Order();
        $ticket = $this->cart->generateTicket($order);
        $this->assertSame($order, $ticket->getOrderTickets());

    }

    public function testPayment()
    {

        $order = new Order();
        $order->setTotalPrice(10);
        $order->setIdOrder("GSjjsh54sSNZ7");


        $this->cartMock
            ->method('getOrder')
            ->willReturn($order);

        $this->checkoutMock
            ->method('charge')
            ->willReturn(true);

        $this->assertTrue($this->cartMock->payment());


    }


}