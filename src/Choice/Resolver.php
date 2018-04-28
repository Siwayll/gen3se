<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Rand;

class Resolver
{
    private $result;

    public function __construct(Choice $choice)
    {
        $choiceOptions = $choice->getOptionCollection();
        $rand = new Rand(0, $choiceOptions->getTotalWeight());
        $rand->roll();
        try {
            $this->result = $choiceOptions->findByPositionInStack($rand->getResult());
        } catch (\Siwayll\Kapow\Exception $exception) {
            if ($exception instanceof ChoiceNameInterface) {
                $exception->setChoiceName($choice->getName());
            }
            throw $exception;
        }
    }

    public function getPickedOption(): Option
    {
        return $this->result;
    }
}
