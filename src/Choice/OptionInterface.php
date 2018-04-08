<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

// @todo simplify this !
use Gen3se\Engine\Choice\Option\DataInterface;

interface OptionInterface
{
    public function getName(): string;
    public function getWeight(): int;
    public function setWeight(int $value): OptionInterface;

    /**
     * Add Data to the option
     */
    public function add(DataInterface $data): OptionInterface;

    /**
     * Convert data of the option in array
     * Doesn't take $name & $weight
     */
    public function dataToArray(): array;

    /**
     * Find all Data who implement the interface $interfaceName
     */
    public function findData($interfaceName);
}
