<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Choice\Option;

interface DataExporterInterface
{
    public function saveFor(Choice $choice, Option $option);
}
