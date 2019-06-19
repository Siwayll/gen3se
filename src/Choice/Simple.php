<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Choice\Option\CollectionInterface as OptionCollectionInterface;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyCollectionOfOptions;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyName;
use Gen3se\Engine\Step;

class Simple implements Choice
{
    /**
     * Name of the Choice
     */
    protected $name;

    /**
     * Collection of Option of the
     */
    protected $optionCollection;

    /**
     * Specific data of the Choice
     */
    protected $data = [];

    /**
     * Choice constructor.
     */
    public function __construct(string $choiceName, OptionCollectionInterface $optionCollection)
    {
        if (empty($choiceName)) {
            throw new MustHaveNonEmptyName();
        }
        $this->name = $choiceName;

        if (\count($optionCollection) === 0) {
            throw new MustHaveNonEmptyCollectionOfOptions($this->name);
        }

        $this->optionCollection = $optionCollection;
    }

    /**
     * Add custom Data to the Choice
     */
    public function add(Data $data): Choice
    {
        $this->data[] = $data;
        return $this;
    }

    /**
     * Get all the Data who implement $interfaceName
     */
    public function findData($interfaceName)
    {
        foreach ($this->data as $data) {
            if (\in_array($interfaceName, \class_implements($data))) {
                yield $data;
            }
        }
    }

    /**
     * Get Name of the Choice
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the collection of Option in the Choice
     */
    public function getOptionCollection(): OptionCollectionInterface
    {
        return $this->optionCollection;
    }

    /**
     * Process the steps sequentially
     */
    public function treatsThis(Step ...$step): void
    {
        $temporaryChoice = clone $this;
        $pickedOption = null;
        foreach ($step as $theStep) {
            switch (true) {
                case $theStep instanceof Step\PostResolve:
                    if ($pickedOption === null) {
                        throw new \RuntimeException();
                    }
                    $theStep($temporaryChoice, $pickedOption);
                    break;
                case $theStep instanceof Step\Resolve:
                    $pickedOption = $theStep($temporaryChoice);
                    break;
                case $theStep instanceof Step\Primary:
                    $theStep($temporaryChoice);
            }
        }
    }

    public function __clone()
    {
        $this->optionCollection = clone $this->optionCollection;
    }
}
