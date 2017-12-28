<?php

namespace Gen3se\Engine\Option;

use Gen3se\Engine\Exception\OptionNotFound;

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
     * @throws OptionNotFound
     */
    public function get(string $optionName): Option
    {
        if (!isset($this->container[$optionName])) {
            throw new OptionNotFound($optionName);
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
}
