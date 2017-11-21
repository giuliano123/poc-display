<?php

namespace DisplayBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpFoundation\Response;

class PlaceControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    protected static $application;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        self::runCommand('doctrine:fixtures:load --no-interaction');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
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
            array('/admin/event/'),
//            array('/admin/event/new'),
            array('/admin/event/place'),
            array('/admin/event/place/new')
        );
    }

//    public function testShowPlace()
//    {
//        $client = $this->getClient();
//
//        $crawler = $client->request('GET', '/admin/event/place');
//
//        $this->assertEquals(
//            Response::HTTP_OK,
//            $client->getResponse()->getStatusCode()
//        );
//
//        $this->assertGreaterThan(
//            0,
//            $crawler->filter('html:contains("Module lieu")')->count()
//        );
//
//        $this->assertContains(
//            'Module lieu',
//            $client->getResponse()->getContent()
//        );
//    }

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