<?php

namespace Gen3se\Engine\Constraint;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Engine;

abstract class Rule
{
    abstract public function selectResult(Engine $engine, Choice $choice);
}
