<?php

namespace Siwayll\Gen3se\Constraint;

use Siwayll\Gen3se\Choice;
use Siwayll\Gen3se\Engine;

abstract class Rule
{
    abstract public function selectResult(Engine $engine, Choice $choice);
}