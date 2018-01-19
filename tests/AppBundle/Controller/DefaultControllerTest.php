<?php

namespace Tests\AppBundle\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

	private $client;

	private $em;

	private $container;

	protected function setUp()
	{

		dump("Setup");

		$this->client = static::createClient();
		
		$this->container = $this->client->getContainer();

		$this->em = $this->container->get('doctrine')->getManager();

	}


    public function testIndex()
    {

        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        //$this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }


	protected function tearDown()
	{

		dump("Teardown");
	}
}
