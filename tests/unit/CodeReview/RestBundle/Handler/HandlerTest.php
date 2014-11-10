<?php

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    // tests
    public function testCanConstruct()
    {
        $repo = $this->getMockRepository();

        $handler = $this->getHandler($repo);

        $this->assertInstanceOf(
            '\CodeReview\RestBundle\Handler\ArtistHandler',
            $handler
        );
    }

    public function testCanGetWithValidId()
    {
        $data = array('hi');

        $repo = $this->getMockRepository();
        $repo->expects($this->once())
            ->method('find')
            ->will($this->returnValue($data))
        ;

        $handler = $this->getHandler($repo);

        $this->assertEquals(
            $data,
            $handler->get(1)
        );
    }

    public function testCantGetWithInvalidId()
    {
        $repo = $this->getMockRepository();
        $repo->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null))
        ;

        $handler = $this->getHandler($repo);

        $this->assertNull(
            $handler->get(1023032023)
        );
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockRepository()
    {
        return $this->getMockBuilder('CodeReview\RestBundle\Repository\ArtistRepository')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param $repo
     * @return \CodeReview\RestBundle\Handler\ArtistHandler
     */
    private function getHandler($repo)
    {
        return new \CodeReview\RestBundle\Handler\ArtistHandler($repo);
    }
}