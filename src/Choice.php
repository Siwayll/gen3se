<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Option\CollectionInterface as OptionCollectionInterface;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyCollectionOfOptions;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyName;

interface Choice
{
    public function getName(): string;

    public function getOptionCollection();
}
