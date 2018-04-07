<?php
declare(strict_types=1);
namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Option\Option;
use Siwayll\RumData\Converter\FromArray;
use Siwayll\RumData\RumData;

/**
 * Stock & Export data from Engine
 */
class DataExporter implements DataExporterInterface, ModInterface
{
    const DATA_STORAGE_RULE = 'dataStorageRule';

    private $storage;

    public function __construct()
    {
        $this->storage = new RumData();
    }

    /**
     * Add __dataStorageRule__ to the instruction list
     * exec _dataValidator_ for validate data in the instruction
     */
    public function getInstructions(): array
    {
        return [
            new Instruction(
                self::DATA_STORAGE_RULE,
                [$this, 'dataValidator'],
                function () {
                }
            )
        ];
    }

    /**
     * Validate instruction value
     */
    public function instructionDataValidator($value): bool
    {
        if (is_string($value) !== true) {
            return false;
        }

        if (substr($value, 0, 2) !== 'x.') {
            return false;
        }

        return true;
    }

    /**
     * Save Custom data of Option
     * Without StorageRule, data are stored under the name of the Choice
     */
    public function saveFor(Choice $choice, Option $option): self
    {
        $dataToSave = (array) $option->exportCleanFields();

        if (empty($dataToSave)) {
            return $this;
        }

        $dataToSave = FromArray::toRumData($dataToSave);

        $depth = [$choice->getName()];

        if ($choice->get(self::DATA_STORAGE_RULE) !== null) {
            $cleanedRule = substr($choice->get(self::DATA_STORAGE_RULE), 2);
            $depth = explode('.', $cleanedRule);
        }

        return $this->store($depth, $dataToSave);
    }

    /**
     * Store data at the given depth
     */
    private function store(array $depth, $dataToSave)
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

    /**
     * Get all data associated with the Choice Name
     */
    public function get(...$names)
    {
        return $this->storage->get(...$names);
    }
}
