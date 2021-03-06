<?php

namespace DisplayBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class PlaceControllerTest extends WebTestCase
{
    protected static $application;

    protected function setUp()
    {
        self::runCommand('doctrine:fixtures:load --no-interaction');
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->getClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/admin/event/place'),
            array('/admin/event/place/new'),
        );
    }

    public function testShowPlace()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/admin/event/place');

        $this->assertContains(
            'Module lieu',
            $client->getResponse()->getContent()
        );

        $this->assertContains(
            'Liste des lieux',
            $client->getResponse()->getContent()
        );
    }

    public function testPlaceListNotEmpty()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/admin/event/place');

        $this->assertGreaterThan(
            0,
            $crawler->filter('.panel table.table tbody tr')->count()
        );
    }

    public function testplaceListHasEditAction()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/admin/event/place');

        $this->assertContains(
            'Editer le lieu',
            $client->getResponse()->getContent()
        );
    }

    public function testEditLink()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/admin/event/place');

        $placeLink = $crawler->filter('.action a')->first();
        $placeTitle = $crawler->filter('.title')->text();

        $crawler    = $client->click($placeLink->link());

        $this->assertContains(
            $placeTitle,
            $client->getResponse()->getContent()
        );
    }

    public function testPlaceHasNewLink()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/admin/event/place');

        $this->assertContains(
            'Ajouter un lieu',
            $client->getResponse()->getContent()
        );

        $newLink = $crawler->filter('.bloc-filters a')->first();

        $crawler    = $client->click($newLink->link());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains(
            'Ajouter un lieu',
            $client->getResponse()->getContent()
        );
    }

    protected function getClient()
    {
        $client = static::createClient();
        $client->setServerParameter('HTTP_HOST', 'poc.devbackoffice.etf1.tf1.fr');

        return $client;
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }
}