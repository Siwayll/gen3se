<?php

namespace Siwayll\Histoire\Constraint;

use Siwayll\Histoire\Choice;
use Siwayll\Histoire\Engine;

abstract class Rule
{
    abstract public function selectResult(Engine $engine, Choice $choice);
}