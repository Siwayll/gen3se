<?php
declare(strict_types = 1);

namespace Gen3se\Engine\Option;

use Gen3se\Engine\Exception\Option\CantUnsetMandatoryData;
use Gen3se\Engine\Exception\Option\MustHaveNonEmptyName;
use Gen3se\Engine\Exception\Option\MustHaveWeightGreaterThanZero;
use Gen3se\Engine\Exception\Option\CannotChangeItsName;

class Option implements \ArrayAccess
{
    private $name;

    private $weight;

    /**
     * Option constructor.
     * @param string $name
     * @param int $weight
     * @throws MustHaveNonEmptyName
     * @throws MustHaveWeightGreaterThanZero
     */
    public function __construct(string $name, int $weight)
    {
        $this->name = $this->controledNameValue($name);
        $this->setWeight($weight);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param int $value
     * @return Option
     * @throws MustHaveWeightGreaterThanZero
     */
    public function setWeight(int $value): self
    {
        if ($value < 0) {
            throw new MustHaveWeightGreaterThanZero($this->getName());
        }
        $this->weight = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return string
     * @throws MustHaveNonEmptyName
     */
    private function controledNameValue(string $value): string
    {
        if (empty($value)) {
            throw new MustHaveNonEmptyName();
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


    public function offsetSet($offset, $value): void
    {
        if ($offset === 'name') {
            throw new CannotChangeItsName($this->getName());
        }
        if ($offset === 'weight') {
            $this->setWeight($value);
            return;
        }
        $this->$offset = $value;
    }

    /**
     * @param mixed $offset
     * @throws CantUnsetMandatoryData
     */
    public function offsetUnset($offset): void
    {
        if ($offset === 'name' || $offset === 'weight') {
            throw new CantUnsetMandatoryData($this->name);
        }

        unset($this->$offset);
    }
}
