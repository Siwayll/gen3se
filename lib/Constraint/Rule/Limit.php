<?php

namespace Gen3se\Engine\Constraint\Rule;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Constraint\Rule;
use Gen3se\Engine\Engine;

class Limit extends Rule
{
    private $value;

    public function __construct($field, $value, $operator = '=')
    {
        $this->field = $field;
        $this->value = $value;
        $this->operator = $operator;
    }

    public function selectResult(Engine $engine, Choice $choice)
    {
        $choice->load();
        foreach ($choice->getOptionNames() as $name) {
            $option = $choice->getLoadedOption($name);

            if (!isset($option[$this->field])) {
                $choice->updateLoaded($name, ['_set' => ['weight' => 0]]);
                continue;
            }

            if (is_array($option[$this->field]) && !is_array($this->value)) {
                if (!in_array($this->value, $option[$this->field])) {
                    $choice->updateLoaded($name, ['_set' => ['weight' => 0]]);
                    continue;
                }
                continue;
            }

            if ($option[$this->field] != $this->value) {
                $choice->updateLoaded($name, ['_set' => ['weight' => 0]]);
                continue;
            }
        }

        $result = $choice
            ->roll()
            ->getResult()
        ;

        return $result;
    }
}
