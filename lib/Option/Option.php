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
        $this->name = $this->controledNameValue($name);
        $this->weight = $weight;
    }

    /**
     * @param string $value
     * @return string
     * @throws OptionMustHaveNonEmptyName
     */
    private function controledNameValue(string $value): string
    {
        if (empty($value)) {
            throw new OptionMustHaveNonEmptyName();
        }
        return $value;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool 
    {
        return isset($this->$offset);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->$offset) ? $this->$offset : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws OptionMustHaveNonEmptyName
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === 'name') {
            $this->name = $this->controledNameValue($value);
            return;
        }
        $this->$offset = $value;
    }

    /**
     * @param mixed $offset
     * @throws OptionCantUnsetMandatoryData
     */
    public function offsetUnset($offset)
    {
        if ($offset === 'name' || $offset === 'weight') {
            throw new OptionCantUnsetMandatoryData($this->name);
        }

        unset($this->$offset);
    }
}
