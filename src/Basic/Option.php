<?php declare(strict_types = 1);

namespace Gen3se\Engine\Basic;

use Gen3se\Engine\Choice\Option as OptionInterface;
use Gen3se\Engine\Choice\Option\CollectionInterface;
use Gen3se\Engine\Choice\Panel;
use Gen3se\Engine\Data;
use Gen3se\Engine\Exception\Option\MustHaveNonEmptyName;
use Gen3se\Engine\Exception\Option\MustHaveWeightGreaterThanZero;

class Option implements OptionInterface
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
//    public function __construct(int $weight, Data ...$data)
    public function __construct(string $name, int $weight)
    {
        $this->name = $this->controledNameValue($name);
        $this->add(new OptionInterface\Data\Text($this->name));
        $this->setWeight($weight);
    }

    /**
     * Change the weight
     *
     * # Exceptions
     * If the new value is not a integer greater than zero.
     */
    public function setWeight(int $value): OptionInterface
    {
        if ($value < 0) {
            throw new MustHaveWeightGreaterThanZero($this->name);
        }
        $this->weight = $value;
        return $this;
    }

    public function isSelectable(): bool
    {
        if ($this->weight === 0) {
            return false;
        }
        return true;
    }

    public function incrementOfWeight(int &$weight): void
    {
        $weight += $this->weight;
    }

    public function signUpTo(Panel $panel): void
    {
        $panel->addOption($this->name, $this);
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
    public function add(Data $data): OptionInterface
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
            $arrayToReturn = \array_merge_recursive($arrayToReturn, $data->toArray());
        }
        return $arrayToReturn;
    }

    public function findData(string $interface): \Generator
    {
        foreach ($this->data as $data) {
            if (\in_array($interface, \class_implements($data))) {
                yield $data;
            }
        }
    }
}
