<?php

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Exception\ChoiceMustHaveNonEmptyCollectionOfOptions;
use Gen3se\Engine\Exception\ChoiceMustHaveNonEmptyName;
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
     * @throws ChoiceMustHaveNonEmptyCollectionOfOptions
     * @throws ChoiceMustHaveNonEmptyName
     */
    public function __construct(string $choiceName, OptionCollection $optionCollection)
    {
        if (empty($choiceName)) {
            throw new ChoiceMustHaveNonEmptyName();
        }
        $this->name = $choiceName;

        if (count($optionCollection) < 1) {
            throw new ChoiceMustHaveNonEmptyCollectionOfOptions($this->name);
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
}
