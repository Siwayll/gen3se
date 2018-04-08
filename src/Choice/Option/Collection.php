<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option;

use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Exception\Option\AlreadyPresent;
use Gen3se\Engine\Exception\Option\NotFound;
use Gen3se\Engine\Exception\Option\NotFoundInStack;
use Gen3se\Engine\Exception\Option\PositionMustBeRelevent;

/**
 * Class Collection
 * @package Gen3se\Engine\Option
 */
class Collection implements \Countable, CollectionInterface
{
    /**
     * @var array
     */
    private $container = [];


    /**
     * Create a collection of Options
     */
    public function __construct(Option ...$option)
    {
        foreach ($option as $optionElmt) {
            $this->add($optionElmt);
        }
    }

    /**
     * Clone all the Option in the container
     */
    public function __clone()
    {
        $oldContainer = $this->container;
        $this->container = [];
        foreach ($oldContainer as $option) {
            $this->container[$option->getName()] = clone $option;
        }
    }

    public function add(Option $option): CollectionInterface
    {
        if (isset($this->container[$option->getName()])) {
            throw new AlreadyPresent($option->getName());
        }
        $this->container[$option->getName()] = $option;

        return $this;
    }

    public function get(string $optionName): Option
    {
        if (!isset($this->container[$optionName])) {
            throw new NotFound($optionName);
        }
        return isset($this->container[$optionName])? $this->container[$optionName]: null;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->container);
    }

    /**
     * @return int
     */
    public function getTotalWeight(): int
    {
        $total = 0;
        array_walk($this->container, function (Option $option) use (&$total) {
            $total += $option->getWeight();
        });

        return $total;
    }

    public function findByPositionInStack(int $position): Option
    {
        if ($position < 0 || $position > $this->getTotalWeight()) {
            throw new PositionMustBeRelevent($position, $this->getTotalWeight());
        }

        $cursor = 0;
        foreach ($this->container as $option) {
            if ($option->getWeight() === 0) {
                continue;
            }
            $cursor += $option->getWeight();
            if ($cursor >= $position) {
                return $option;
            }
        }

        throw new NotFoundInStack($position);
    }

    /**
     * Iterates over options list
     */
    public function each(): \Generator
    {
        foreach ($this->container as $option) {
            yield $option;
        }
    }
}
