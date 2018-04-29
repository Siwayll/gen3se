<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Result\Filer;

interface Result
{
    public function registersTo(Option $option, Filer $filer): void;
}
