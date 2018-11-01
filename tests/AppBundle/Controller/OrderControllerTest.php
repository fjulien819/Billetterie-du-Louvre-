<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 01/11/2018
 * Time: 12:50
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testNewOrder()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Commander vos billets")')->count());


        $form = $crawler->selectButton('Commander')->form();

        $form['appbundle_ordertickets[email][first]'] = 'test@test.com';
        $form['appbundle_ordertickets[email][second]']= 'test@test.com';
        $form['appbundle_ordertickets[nbrTickets]'] = 1;
        $form['appbundle_ordertickets[visiteDay]'] = '2020-12-09';
        $form['appbundle_ordertickets[ticketType]'] = 'journee';

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Création des billets")')->count());


        $form = $crawler->selectButton('Ajouter billet')->form();

        $form['appbundle_ticket[name]'] = 'Jean';
        $form['appbundle_ticket[lastName]']= 'Dupont';
        $form['appbundle_ticket[country]'] = 'FR';
        $form['appbundle_ticket[birthDate][month]'] = '2';
        $form['appbundle_ticket[birthDate][day]'] = '1';
        $form['appbundle_ticket[birthDate][year]'] = '1990';


        $client->submit($form);

        $crawler = $client->followRedirect();


        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Récapitulatif de la commande")')->count());



    }

}