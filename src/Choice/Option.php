<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Data;

interface Option
{
    /**
     * Change the weight of the option
     */
    public function setWeight(int $value): Option;

    public function signUpTo(Panel $panel): void;

    /**
     * Inform if the option is selectable for a result
     */
    public function isSelectable(): bool;

    /**
     * Increment the given weight number of the weight option
     */
    public function incrementOfWeight(int &$weight): void;

//    public function markAsSelected(CollectionInterface $options): void;

    // DATA
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
    public function findData(string $interfaceName): \Generator;
}
