<?php

namespace Gen3se\Engine\Constraint\Rule;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Constraint\Rule;
use Gen3se\Engine\Engine;

class Value extends Rule
{
    private $value;
    private $fieldName = 'text';

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function selectResult(Engine $engine, Choice $choice)
    {
        $targetName = $choice->canIForce($this->fieldName, $this->value);
        if ($targetName === false) {
            throw new \Exception('Pour ' . $choice->getName() . ' ' . $this->value . ' non valide');
        }

        return $choice->getOption($targetName);
    }
}