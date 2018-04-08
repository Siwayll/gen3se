<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Choice\Option\Data;

interface Option
{
    public function getName(): string;
    public function getWeight(): int;
    public function setWeight(int $value): Option;

    /**
     * Add Data to the option
     */
    public function add(Data $data): Option;

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
