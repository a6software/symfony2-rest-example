<?php

class ArtistFormTest extends Symfony\Component\Form\Test\TypeTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
    }

    // tests
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'test',
        );

        $type = new \CodeReview\RestBundle\Form\Type\ArtistType();
        $form = $this->factory->create($type);

        $object = new \CodeReview\RestBundle\Entity\Artist();
        $object->setName($formData['name']);

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
}