<?php
declare(strict_types = 1);

namespace Gen3se\Engine\Option;

use Gen3se\Engine\Exception\Option\CantUnsetMandatoryData;
use Gen3se\Engine\Exception\Option\MustHaveNonEmptyName;
use Gen3se\Engine\Exception\Option\MustHaveWeightGreaterThanZero;
use Gen3se\Engine\Exception\Option\CannotChangeItsName;

class Option implements \ArrayAccess
{
    /**
     * Name of the option.
     *
     * The name must be unique in the Choice to witch it
     * is attached
     */
    private $name;

    /**
     * Weight of the option
     *
     * The weight is used to calculate the importance of
     * the option when resolving the choice.
     */
    private $weight;

    /**
     * Custom data
     *
     * Anything that is not the name or the weight
     */
    private $custom = [];

    /**
     * Fields
     */
    private $fieldsToClean = [];

    /**
     * Create a new Option whith a name and a weight
     */
    public function __construct(string $name, int $weight)
    {
        $this->name = $this->controledNameValue($name);
        $this->setWeight($weight);
    }

    /**
     * Add a fieldName to the cleanList
     */
    public function cleanField(string $fieldName): self
    {
        $this->fieldsToClean[$fieldName] = true;
        return $this;
    }

    /**
     * Return all the custom data without fields in the cleanList
     */
    public function exportCleanFields(): array
    {
        $cleanedFields = $this->custom;
        foreach ($this->custom as $fieldName => $value) {
            if (isset($this->fieldsToClean[$fieldName])) {
                unset($cleanedFields[$fieldName]);
            }
        }
        return $cleanedFields;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * Change the weight
     *
     * # Exceptions
     * If the new value is not a integer greater than zero.
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
     * @param string $name
     * @param $value
     * @return Option
     * @throws CannotChangeItsName
     * @throws MustHaveWeightGreaterThanZero
     */
    public function set(string $name, $value): self
    {
        if ($name === 'name') {
            throw new CannotChangeItsName($this->getName());
        }
        if ($name === 'weight') {
            $this->setWeight($value);
            return $this;
        }
        $this->custom[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return int|mixed|null|string
     */
    public function get(string $name)
    {
        if ($name === 'name') {
            return $this->getName();
        }
        if ($name === 'weight') {
            return $this->getWeight();
        }
        return isset($this->custom[$name]) ? $this->custom[$name] : null;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        if ($offset === 'name' || $offset === 'weight') {
            return true;
        }
        return isset($this->custom[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws CannotChangeItsName
     * @throws MustHaveWeightGreaterThanZero
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
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

        unset($this->custom[$offset]);
    }
}
