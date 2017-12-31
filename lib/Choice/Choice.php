<?php

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyCollectionOfOptions;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyName;
use Gen3se\Engine\Option\Collection as OptionCollection;
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

    /**
     * Choice constructor.
     * @param string $choiceName
     * @param OptionCollection $optionCollection
     * @throws MustHaveNonEmptyCollectionOfOptions
     * @throws MustHaveNonEmptyName
     */
    public function __construct(string $choiceName, OptionCollection $optionCollection)
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return OptionCollection
     */
    public function getOptionCollection(): OptionCollection
    {
        return $this->optionCollection;
    }

    public function __clone()
    {
        $this->optionCollection = clone $this->optionCollection;
    }
}
