<?php

namespace Gen3se\Engine\Option;

use Gen3se\Engine\Exception\Option\NotFound;
use Gen3se\Engine\Exception\Option\NotFoundInStack;
use Gen3se\Engine\Exception\Option\PositionMustBeRelevent;

/**
 * Class Collection
 * @package Gen3se\Engine\Option
 */
class Collection implements \Countable
{
    /**
     * @var array
     */
    private $container = [];

    /**
     * @param Option $option
     * @return Collection
     */
    public function add(Option $option): self
    {
        $this->container[$option->getName()] = $option;

        return $this;
    }

    /**
     * @param string $optionName
     * @return Option
     * @throws NotFound
     */
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

    /**
     * @param int $position
     * @return Option
     * @throws NotFoundInStack
     * @throws PositionMustBeRelevent
     */
    public function findByPositonInStack(int $position): Option
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
}
