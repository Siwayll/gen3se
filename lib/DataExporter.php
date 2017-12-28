<?php
namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Option;

class DataExporter implements DataExporterInterface
{
    private $storage = [];

    public function saveFor(Choice $choice, Option $option): self
    {
        if (!isset($this->storage[$choice->getName()])) {
            $this->storage[$choice->getName()] = (array) $option;
            return $this;
        }

        if (!is_array($this->storage[$choice->getName()])) {
            $this->storage[$choice->getName()] = [$this->storage[$choice->getName()]];
        }

        $this->storage[$choice->getName()][] = (array) $option;

        return $this;
    }
}
