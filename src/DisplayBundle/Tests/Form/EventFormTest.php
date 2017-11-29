<?php

namespace DisplayBundle\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EventFormTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    protected $file;
    protected $image;

    protected static $application;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->file = tempnam(sys_get_temp_dir(), 'upl');
        imagepng(imagecreatetruecolor(10, 10), $this->file);
        $this->image = new UploadedFile(
            $this->file,
            'new_image.png'
        );
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;

        unlink($this->file);
    }

    public function testEmptyFormSubmit()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/admin/event/new');

        $form = $crawler->selectButton('Sauvegarder')->form();

        $crawler = $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertRegExp(
            '/Le champ &#039;date de publication&#039; est obligatoire/',
            $client->getResponse()->getContent()
        );
    }

    public function testFormFill()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/admin/event/new');

        $form = $crawler->selectButton('Sauvegarder')->form();

        /** @var Crawler $place */
        $place = $crawler->filter('#event_place')->first();
//        var_dump($place->first());

//        $form['event[publicationDate]'] = date('d/m/Y');
//
//        $form['event[title]'] = 'I am a test';
//
//        $form['event[pictureFile][file]']->upload($this->image);
//        $form['event[posterFile][file]']->upload($this->image);
//
//        $crawler = $client->submit($form);
//        $this->assertTrue($client->getResponse()->isRedirect());
//        $client->followRedirect();
//
//        $this->assertContains(
//            'Succès !',
//            $client->getResponse()->getContent()
//        );
//
//        $this->assertContains(
//            'I am a test',
//            $client->getResponse()->getContent()
//        );
    }


    protected function getClient()
    {
        $client = static::createClient();
        $client->setServerParameter('HTTP_HOST', 'poc.devbackoffice.etf1.tf1.fr');

        return $client;
    }
}