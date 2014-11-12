<?php

class FormHandlerTest extends \Codeception\TestCase\Test
{
    private $serviceContainer;
    private $formFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->formFactory = $this->serviceContainer->get('form.factory');
    }

    protected function tearDown()
    {
    }

    // tests
    public function testCanGrabFromServiceContainer()
    {
        $this->assertInstanceOf(
            'CodeReview\RestBundle\Form\Handler\FormHandler',
            $this->serviceContainer->get('code_review.rest_bundle.form.handler.artist_form_handler')
        );
    }


    /**
     * @expectedException ErrorException
     * @expectedExceptionMessageRegExp /Symfony\\Component\\Form\\FormTypeInterface/
     */
    public function testProcessFormThrowsWhenGivenInvalidFormType()
    {
        new \CodeReview\RestBundle\Form\Handler\FormHandler(
            $this->getMockEntityManager(),
            $this->formFactory,
            new \StdClass()
        );
    }

    /**
     * @expectedException Symfony\Component\Form\Exception\LogicException
     * @expectedExceptionMessageRegExp /CodeReview\\RestBundle\\Entity\\Artist/
     */
    public function testProcessFormThrowsWhenGivenInvalidObjectForAGivenFormType()
    {
        $formHandler = new \CodeReview\RestBundle\Form\Handler\FormHandler(
            $this->getMockEntityManager(),
            $this->formFactory,
            new \CodeReview\RestBundle\Form\Type\ArtistType()
        );

        $formHandler->processForm(new \StdClass(), array(), 'POST');
    }

    /**
     * @expectedException CodeReview\RestBundle\Exception\InvalidFormException
     */
    public function testProcessFormReturnsFormWithErrorsWhenFormIsNotValid()
    {
        $mockForm = $this->getMock('Symfony\Component\Form\FormInterface');
        $mockForm->expects($this->once())
            ->method('submit');

        $mockForm->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $formFactory = $this->getMockBuilder('Symfony\Component\Form\FormFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($mockForm));

        $formHandler = new \CodeReview\RestBundle\Form\Handler\FormHandler(
            $this->getMockEntityManager(),
            $formFactory,
            new \CodeReview\RestBundle\Form\Type\ArtistType()
        );

        $formHandler->processForm(new \CodeReview\RestBundle\Entity\Artist(), array(), 'POST');
    }

    public function testProcessFormReturnsValidObjectOnSuccess()
    {
        $formHandler = new \CodeReview\RestBundle\Form\Handler\FormHandler(
            $this->getMockEntityManager(),
            $this->formFactory,
            new \CodeReview\RestBundle\Form\Type\ArtistType()
        );

        $this->assertInstanceOf(
            '\CodeReview\RestBundle\Entity\Artist',
            $formHandler->processForm(new \CodeReview\RestBundle\Entity\Artist(), array(), "POST")
        );
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockEntityManager()
    {
        return $this->getMock('Doctrine\Common\Persistence\ObjectManager');
    }
}