<?php

namespace Gen3se\Engine\Option;

use Gen3se\Engine\Exception\OptionNotFound;

/**
 * Class Collection
 * @package Gen3se\Engine\Option
 */
class Collection
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
}
