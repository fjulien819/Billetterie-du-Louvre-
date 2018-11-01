<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 29/10/2018
 * Time: 15:23
 */

namespace Tests\AppBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ApplicationAvailabilityFunctionalTest extends WebTestCase
{

    /**
     * @param $url
     * @param $expectedStatusCode
     * @dataProvider urlProviderWithoutOrder
     */

   public function testPageIsSuccessfulWithoutOrder($url, $expectedStatusCode)
    {
        $client = self::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);


        $this->assertSame($expectedStatusCode, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Commander vos billets")')->count());
    }

    public function urlProviderWithoutOrder()
    {
        return array(
            array('/', 200),
            array('/order', 200),
            array('/summary', 200),
        );
    }




}