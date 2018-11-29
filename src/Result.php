<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Result\Filer;

interface Result extends Choice
{
    public function registersTo(Filer $filer): void;
}
