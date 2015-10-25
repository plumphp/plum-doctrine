<?php

namespace Plum\PlumDoctrine\ORM;

use Mockery;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use stdClass;

/**
 * EntityWriterTest.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class EntityWriterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface|\Mockery\MockInterface
     */
    private $entityManager;

    public function setUp()
    {
        $this->entityManager = Mockery::mock('\Doctrine\ORM\EntityManagerInterface');
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\EntityWriter::prepare()
     */
    public function prepareShouldResetPersistCount()
    {
        $this->entityManager->shouldReceive('persist');

        $writer = new EntityWriter($this->entityManager);
        $writer->writeItem(new stdClass());
        $writer->prepare();

        $this->assertSame(0, $this->getReflectionProperty($writer, 'persistCount'));
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\EntityWriter::writeItem()
     */
    public function writeItemPersistsItemInEntityManager()
    {
        $entity = new stdClass();

        $this->entityManager->shouldReceive('persist')->with($entity)->once();

        $writer = new EntityWriter($this->entityManager);
        $writer->writeItem($entity);
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\EntityWriter::writeItem()
     * @covers Plum\PlumDoctrine\ORM\EntityWriter::finish()
     * @covers Plum\PlumDoctrine\ORM\EntityWriter::shouldFlush()
     */
    public function writeItemShouldFlushIfFlushIntervalIsSet()
    {
        $entity = new stdClass();

        $this->entityManager->shouldReceive('persist')->with($entity)->times(2);
        $this->entityManager->shouldReceive('flush')->once();

        $writer = new EntityWriter($this->entityManager, ['flushInterval' => 2]);
        $writer->writeItem($entity);
        $writer->writeItem($entity);
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\EntityWriter::finish()
     */
    public function finishFlushesTransactions()
    {
        $this->entityManager->shouldReceive('persist');
        $this->entityManager->shouldReceive('flush')->once();

        $writer = new EntityWriter($this->entityManager);
        $writer->writeItem(new stdClass());
        $writer->finish();
    }

    /**
     * @test
     * @covers Plum\PlumDoctrine\ORM\EntityWriter::finish()
     */
    public function finishShouldOnlyFlushIfItemIsWritten()
    {
        $this->entityManager->shouldReceive('flush')->never();

        $writer = new EntityWriter($this->entityManager);
        $writer->finish();
    }

    /**
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    protected function getReflectionProperty($object, $property)
    {
        $reflectionClass    = new ReflectionClass(get_class($object));
        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }
}
