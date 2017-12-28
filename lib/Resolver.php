<?php
namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Option;

class Resolver
{
    private $result;

    /**
     * Resolver constructor.
     * @param Choice $choice
     * @throws Exception\Option\NotFoundInStack
     * @throws Exception\Option\PositionMustBeRelevent
     */
    public function __construct(Choice $choice)
    {
        $choiceOptions = $choice->getOptionCollection();
        $rand = new Rand(0, $choiceOptions->getTotalWeight());
        $rand->roll();
        $this->result = $choiceOptions->findByPositonInStack($rand->getResult());
    }

    /**
     * @return Option
     */
    public function getPickedOption(): Option
    {
        return $this->result;
    }
}
