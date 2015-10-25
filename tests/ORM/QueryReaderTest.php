<?php

namespace Plum\PlumDoctrine\ORM;

use Doctrine\ORM\AbstractQuery;
use Mockery;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * QueryReaderTest.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class QueryReaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\AbstractQuery|\Mockery\MockInterface
     */
    private $query;

    public function setUp()
    {
        $this->query = Mockery::mock('\Doctrine\ORM\AbstractQuery');
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\QueryReader::getIterator()
     */
    public function getIteratorShouldReturnIteratorForQuery()
    {
        $result = new stdClass();
        $this->query->shouldReceive('getResult')->once()->andReturn([$result]);

        $reader   = new QueryReader($this->query);
        $iterator = $reader->getIterator();

        $this->assertInstanceOf('\Iterator', $iterator);
        foreach ($iterator as $entity) {
            $this->assertEquals($result, $entity);
        }
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\QueryReader::getIterator()
     */
    public function getIteratorShouldPassHydrationModeToDoctrine()
    {
        $result = new stdClass();
        $this->query->shouldReceive('getResult')->with(AbstractQuery::HYDRATE_ARRAY)->once()->andReturn([$result]);

        $reader   = new QueryReader($this->query, ['hydrationMode' => AbstractQuery::HYDRATE_ARRAY]);
        $iterator = $reader->getIterator();

        $this->assertInstanceOf('\Iterator', $iterator);
        foreach ($iterator as $entity) {
            $this->assertEquals($result, $entity);
        }
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\QueryReader::getIterator()
     */
    public function getIteratorShouldCallGetResultOnlyOnce()
    {
        $this->query->shouldReceive('getResult')->once()->andReturn([]);

        $reader = new QueryReader($this->query);
        $reader->getIterator();
        $reader->getIterator();
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\QueryReader::count()
     */
    public function countShouldReturnNumberOfResults()
    {
        $result = new stdClass();
        $this->query->shouldReceive('getResult')->andReturn([$result, $result]);

        $reader = new QueryReader($this->query);

        $this->assertEquals(2, $reader->count());
    }
}
