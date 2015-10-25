<?php

namespace Plum\PlumDoctrine\ORM;

use Mockery;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * RepositoryReaderTest.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class RepositoryReaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityRepository|\Mockery\MockInterface
     */
    private $repository;

    public function setUp()
    {
        $this->repository = Mockery::mock('\Doctrine\ORM\EntityRepository');
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\RepositoryReader::getIterator()
     */
    public function getIteratorReturnsIteratorForResults()
    {
        $expected = new stdClass();
        $this->repository->shouldReceive('findBy')->with(['age' => 29])->once()->andReturn([$expected]);

        $reader   = new RepositoryReader($this->repository, ['age' => 29]);
        $iterator = $reader->getIterator();

        $this->assertInstanceOf('\Iterator', $iterator);
        foreach ($iterator as $entity) {
            $this->assertEquals($expected, $entity);
        }
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\RepositoryReader::getIterator()
     */
    public function getIteratorShouldCallFindByOnlyOnce()
    {
        $this->repository->shouldReceive('findBy')->once()->andReturn([]);

        $reader = new RepositoryReader($this->repository, ['age' => 29]);
        $reader->getIterator();
        $reader->getIterator();
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\RepositoryReader::count()
     */
    public function countShouldReturnNumberOfResults()
    {
        $result = new stdClass();
        $this->repository->shouldReceive('findBy')
                         ->andReturn([$result, $result]);

        $reader = new RepositoryReader($this->repository, ['age' => 29]);

        $this->assertEquals(2, $reader->count());
    }
}
