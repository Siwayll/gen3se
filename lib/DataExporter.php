<?php
namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Option;

/**
 * Stock & Export data from Engine
 */
class DataExporter implements DataExporterInterface
{
    private $storage = [];

    private function hasNonNumericKeys($array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * Save Custom data of Option with Choice name for path
     */
    public function saveFor(Choice $choice, Option $option): self
    {
        $dataToSave = (array) $option->exportCleanFields();

        if (empty($dataToSave)) {
            return $this;
        }

        if (!isset($this->storage[$choice->getName()])) {
            $this->storage[$choice->getName()] = $dataToSave;
            return $this;
        }

        if ($this->hasNonNumericKeys($this->storage[$choice->getName()])) {
            $this->storage[$choice->getName()] = [$this->storage[$choice->getName()]];
        }

        $this->storage[$choice->getName()][] = $dataToSave;

        return $this;
    }

    /**
     * Get all data associated with the Choice Name
     */
    public function get($choiceName)
    {
        return isset($this->storage[$choiceName]) ? $this->storage[$choiceName] : null;
    }
}
