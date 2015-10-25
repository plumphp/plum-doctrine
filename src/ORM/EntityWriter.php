<?php

namespace Plum\PlumDoctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Plum\Plum\Writer\WriterInterface;

/**
 * EntityWriter.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2015 Florian Eckerstorfer
 */
class EntityWriter implements WriterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array
     */
    private $options = [
        'flushInterval' => null,
    ];

    /**
     * @var int
     */
    private $persistCount = 0;

    /**
     * @param EntityManagerInterface $entityManager
     * @param array                  $options
     *
     * @codeCoverageIgnore
     */
    public function __construct(EntityManagerInterface $entityManager, array $options = [])
    {
        $this->entityManager = $entityManager;
        $this->options       = array_merge($this->options, $options);
    }

    /**
     * Write the given item.
     *
     * @param mixed $item
     */
    public function writeItem($item)
    {
        $this->entityManager->persist($item);
        ++$this->persistCount;
        if ($this->options['flushInterval'] && $this->shouldFlush()) {
            $this->finish();
        }
    }

    /**
     * Prepare the writer.
     */
    public function prepare()
    {
        $this->persistCount = 0;
    }

    /**
     * Finish the writer.
     */
    public function finish()
    {
        $this->persistCount > 0 && $this->entityManager->flush();
    }

    /**
     * @return bool
     */
    protected function shouldFlush()
    {
        return ($this->persistCount % $this->options['flushInterval']) === 0;
    }
}
