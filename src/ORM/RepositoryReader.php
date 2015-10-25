<?php

namespace Plum\PlumDoctrine\ORM;

use ArrayIterator;
use Doctrine\ORM\EntityRepository;
use Plum\Plum\Reader\ReaderInterface;

/**
 * RepositoryReader.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2015 Florian Eckerstorfer
 */
class RepositoryReader implements ReaderInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var array
     */
    private $condition;

    /**
     * @var ArrayIterator
     */
    private $iterator;

    /**
     * @param EntityRepository $repository
     * @param array            $condition
     *
     * @codeCoverageIgnore
     */
    public function __construct(EntityRepository $repository, array $condition)
    {
        $this->repository = $repository;
        $this->condition  = $condition;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return $this->iterator ?
            $this->iterator :
            $this->iterator = new ArrayIterator($this->repository->findBy($this->condition));
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getIterator());
    }
}
