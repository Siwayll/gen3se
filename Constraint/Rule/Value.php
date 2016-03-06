<?php

namespace Siwayll\Histoire\Constraint\Rule;

use Siwayll\Histoire\Choice;
use Siwayll\Histoire\Constraint\Rule;
use Siwayll\Histoire\Engine;

class Value extends Rule
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function selectResult(Engine $engine, Choice $choice)
    {
        if ($choice->canIForce($this->value) === false) {
            throw new \Exception('Pour ' . $choice->getName() . ' ' . $this->value . ' non valide');
        }

        return $choice->getOption($this->value);
    }
}