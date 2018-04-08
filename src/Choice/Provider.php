<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Choice;
use Gen3se\Engine\ChoiceProviderInterface;
use Gen3se\Engine\Exception\Choice\NotFound;

/**
 * Simple Choice Provider
 */
class Provider implements ChoiceProviderInterface
{
    /**
     * List of choices
     */
    private $container = [];

    /**
     * Add a Choice to the Provider
     */
    public function add(Choice $choice): self
    {
        $this->container[$choice->getName()] = $choice;

        return $this;
    }

    /**
     * Get a choice by it's name
     * # Exceptions
     * if the choice can't be loaded
     */
    public function get(string $choiceName): Choice
    {
        if (!isset($this->container[$choiceName])) {
            throw new NotFound($choiceName);
        }
        return $this->container[$choiceName];
    }

    /**
     * Say if the choice is present in the provider
     */
    public function hasChoice(string $choiceName): bool
    {
        if (isset($this->container[$choiceName])) {
            return true;
        }
        return false;
    }
}
