<?php

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Choice\OptionInterface;

interface DataExporterInterface
{
    public function saveFor(Choice $choice, OptionInterface $option);
}
