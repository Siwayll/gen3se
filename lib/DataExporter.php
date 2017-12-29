<?php
namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Option;

class DataExporter implements DataExporterInterface
{
    private $storage = [];

    /**
     * @param $array
     * @return bool
     */
    private function hasNonNumericKeys($array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * @param Choice $choice
     * @param Option $option
     * @return DataExporter
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
     * @param $choiceName
     * @return mixed|null
     */
    public function get($choiceName)
    {
        return $this->storage[$choiceName] ? $this->storage[$choiceName] : null;
    }
}
