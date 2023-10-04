<?php

namespace App\Tests\Shared\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class HealthCheckControllerTest extends WebTestCase {

    public function testRequestRespondedSuccessfulResult() : void {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/health-check');

        $this->assertResponseIsSuccessful();
        $jsonResult = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($jsonResult['status'], 'ok');
    }
}