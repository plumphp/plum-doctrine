<?php

namespace Plum\PlumDoctrine\ORM;

use ArrayIterator;
use Doctrine\ORM\AbstractQuery;
use Plum\Plum\Reader\ReaderInterface;

/**
 * QueryReader.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2015 Florian Eckerstorfer
 */
class QueryReader implements ReaderInterface
{
    /**
     * @var AbstractQuery
     */
    private $query;

    /**
     * @var array
     */
    private $options = [
        'hydrationMode' => AbstractQuery::HYDRATE_OBJECT,
    ];

    /**
     * @var ArrayIterator
     */
    private $iterator;

    /**
     * @param AbstractQuery $query
     * @param array         $options
     *
     * @codeCoverageIgnore
     */
    public function __construct(AbstractQuery $query, array $options = [])
    {
        $this->query   = $query;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return $this->iterator ?
            $this->iterator :
            $this->iterator = new ArrayIterator($this->query->getResult($this->options['hydrationMode']));
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getIterator());
    }
}
