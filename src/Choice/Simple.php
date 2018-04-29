<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Choice\Option\CollectionInterface as OptionCollectionInterface;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyCollectionOfOptions;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyName;

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

    public function __clone()
    {
        $this->optionCollection = clone $this->optionCollection;
    }
}
