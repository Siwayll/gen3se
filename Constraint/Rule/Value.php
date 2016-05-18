<?php

namespace Siwayll\Gen3se\Constraint\Rule;

use Siwayll\Gen3se\Choice;
use Siwayll\Gen3se\Constraint\Rule;
use Siwayll\Gen3se\Engine;

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