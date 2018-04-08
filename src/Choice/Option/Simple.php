<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option;

use Gen3se\Engine\Choice\Option as Option;
use Gen3se\Engine\Exception\Option\MustHaveNonEmptyName;
use Gen3se\Engine\Exception\Option\MustHaveWeightGreaterThanZero;

class Simple implements Option
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
     * Data carry by the option
     */
    private $data = [];

    /**
     * Create a new Option with a name and a weight
     */
    public function __construct(string $name, int $weight)
    {
        $this->name = $this->controledNameValue($name);
        $this->add(new Data\Text($this->name));
        $this->setWeight($weight);
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
    public function setWeight(int $value): Option
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
     * Add data to the option
     */
    public function add(Data $data): Option
    {
        $this->data[] = $data;
        return $this;
    }

    /**
     * Convert data of the option in array
     * Doesn't take $name & $weight
     */
    public function dataToArray(): array
    {
        $arrayToReturn = [];
        foreach ($this->data as $data) {
            $arrayToReturn = array_merge_recursive($arrayToReturn, $data->toArray());
        }
        return $arrayToReturn;
    }

    public function findData($interface)
    {
        foreach ($this->data as $data) {
            if (in_array($interface, class_implements($data))) {
                yield $data;
            }
        }
    }
}
