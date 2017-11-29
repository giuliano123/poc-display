<?php

namespace DisplayBundle\Tests\Form;

use DisplayBundle\Entity\Place;
use DisplayBundle\Form\PlaceType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PlaceTest extends TypeTestCase
{
    protected $file;
    protected $image;

    protected function setUp()
    {
//        $this->markTestSkipped(
//            'Je n\'arrive pas à gérer le champ VichFileType'
//        );

        parent::setUp();

        $this->factory = Forms::createFormFactoryBuilder()
            ->addTypeExtension(new FormTypeValidatorExtension(
                $this->getMockBuilder(ValidatorInterface::class)
                    ->disableOriginalConstructor()
                    ->getMock())
            )
            ->addTypeGuesser(
                $this->getMockBuilder(ValidatorTypeGuesser::class)
                    ->disableOriginalConstructor()
                    ->getMock()
            )
            ->addTypes(array(
                $this->getMockBuilder(VichFileType::class)
                    ->disableOriginalConstructor()
                    ->getMock()

            ))
            ->getFormFactory();

        $this->dispatcher =$this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);

//var_dump($this->factory);
        $this->file = tempnam(sys_get_temp_dir(), 'upl');
        imagepng(imagecreatetruecolor(10, 10), $this->file);
        $this->image = new UploadedFile(
            $this->file,
            'new_image.png'
        );
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'title' => 'I am a test 2 !',
            'imageFile' => $this->image,
        );

        $object = new Place();

        $form = $this->factory->create(PlaceType::class, $object);

        $object->setTitle('I am a test 2 !');
        $object->setImageFile($this->image);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

//    protected function getTypes()
//    {
//        $types = array();
//        $kernel = self::bootKernel();

//        $container = $kernel->getContainer();

//        $vichFileType = $this->getMockBuilder(VichFileType::class)
//            ->setMethods(array('__construct'))
//            ->setConstructorArgs(array($container->get('vich_uploader.storage'), $container->get('vich_uploader.upload_handler'), $container->get('vich_uploader.property_mapping_factory')))
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $types[] = $vichFileType;
//
//        return array($vichFileType);
//    }
}