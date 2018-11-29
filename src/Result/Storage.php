<?php declare(strict_types = 1);

namespace Gen3se\Engine\Result;

use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Result;
use Siwayll\RumData\Converter\FromArray;
use Siwayll\RumData\RumData;

class Storage
{
    private $storage;

    public function __construct()
    {
        $this->storage = new RumData();
    }

    public function registersTo(Option $option, Filer $filer): void
    {
        $dataToSave = $option->dataToArray();

        if (empty($dataToSave)) {
            return;
        }

        $dataToSave = FromArray::toRumData($dataToSave);
        $this->store($dataToSave, ...$filer->getDepth());
    }

    /**
     * Store data at the given depth
     */
    private function store(RumData $dataToSave, string ...$depth)
    {
        // target not present
        if ($this->storage->has(...$depth) !== true) {
            $this->storage->set($dataToSave, ...$depth);
            return $this;
        }

        // target is already a list
        if ($this->storage->isAList(...$depth)) {
            $this->storage->append($dataToSave, ...$depth);
            return $this;
        }

        $dataToSave = $this->createNewList($depth, $dataToSave);
        $this->storage->set($dataToSave, ...$depth);

        return $this;
    }

    /**
     * Convert a standard RumData in a List (cf RumData)
     */
    private function createNewList(array $depth, $dataToSave): RumData
    {
        $alreadyPresent = $this->storage->get(...$depth);
        $tmpData = new RumData();
        $tmpData->set($alreadyPresent, 0);
        $tmpData->set($dataToSave, 1);

        return $tmpData;
    }

    public function dump(): RumData
    {
        return $this->storage;
    }
}
