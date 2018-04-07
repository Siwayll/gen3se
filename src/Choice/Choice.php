<?php

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Choice\Option\CollectionInterface as OptionCollectionInterface;
use Gen3se\Engine\Exception\Choice\CannotChangeItsName;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyCollectionOfOptions;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyName;
use Gen3se\Engine\ChoiceData;

class Choice
{
    /**
     * Nom identifiant le choix
     *
     * @var string
     */
    protected $name;

    protected $optionCollection;

    private $custom = [];

    /**
     * Choice constructor.
     */
    public function __construct(string $choiceName, OptionCollectionInterface $optionCollection)
    {
        if (empty($choiceName)) {
            throw new MustHaveNonEmptyName();
        }
        $this->name = $choiceName;

        if (count($optionCollection) < 1) {
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

    public function set($name, $value): self
    {
        if ($name === 'name') {
            throw new CannotChangeItsName($this->getName());
        }

        $this->custom[$name] = $value;
        return $this;
    }

    public function get($name)
    {
        return isset($this->custom[$name]) ? $this->custom[$name] : null;
    }

    public function exists(string $name): bool
    {
        return isset($this->custom[$name]);
    }

    public function __clone()
    {
        $this->optionCollection = clone $this->optionCollection;
    }
}
