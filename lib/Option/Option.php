<?php

namespace Gen3se\Engine\Option;

use Gen3se\Engine\Exception\OptionCantUnsetMandatoryData;
use Gen3se\Engine\Exception\OptionMustHaveNonEmptyName;

class Option implements \ArrayAccess
{
    private $name;

    private $weight;

    /**
     * Option constructor.
     * @param string $name
     * @param int $weight
     * @throws OptionMustHaveNonEmptyName
     */
    public function __construct(string $name, int $weight)
    {
        if (empty($name)) {
            throw new OptionMustHaveNonEmptyName();
        }
        $this->name = $name;
        $this->weight = $weight;
    }

    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return isset($this->$offset) ? $this->$offset : null;
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        if ($offset === 'name' || $offset === 'weight') {
            throw new OptionCantUnsetMandatoryData($this->name);
        }
        // TODO: Implement offsetUnset() method.
    }
}
