<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Data;
use Gen3se\Engine\Choice\Option\CollectionInterface as OptionCollection;

interface Choice
{
    public function getName(): string;

    public function getOptionCollection(): OptionCollection;

    /**
     * Treats all the Steps with a clone of the Choice
     */
    public function treatsThis(Step ...$step): void;

    /**
     * Add Data to the option
     */
    public function add(Data $data): Choice;

    /**
     * Find all Data who implement the interface $interfaceName
     */
    public function findData($interfaceName);
}
