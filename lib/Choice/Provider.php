<?php

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\ChoiceProviderInterface;
use Gen3se\Engine\Exception\Choice\NotFound;

/**
 * Class Collection
 * @package Gen3se\Engine\Option
 */
class Provider implements ChoiceProviderInterface
{
    /**
     * @var array
     */
    private $container = [];

    /**
     * @param Choice $choice
     * @return Provider
     */
    public function add(Choice $choice): self
    {
        $this->container[$choice->getName()] = $choice;

        return $this;
    }

    /**
     * @param string $choiceName
     * @return Choice
     * @throws NotFound
     */
    public function get(string $choiceName): Choice
    {
        if (!isset($this->container[$choiceName])) {
            throw new NotFound($choiceName);
        }
        return $this->container[$choiceName];
    }
}
