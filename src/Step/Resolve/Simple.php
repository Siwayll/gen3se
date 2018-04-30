<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step\Resolve;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Rand;
use Gen3se\Engine\Result;
use Gen3se\Engine\Step;

class Simple implements Step\Resolve
{
    public function __invoke(Choice $choice): Choice\Option
    {
        $choiceOptions = $choice->getOptionCollection();
        try {
            $rand = new Rand(0, $choiceOptions->getTotalWeight());
            $rand->roll();
            $result = $choiceOptions->findByPositionInStack($rand->getResult());
        } catch (\Siwayll\Kapow\Exception $exception) {
            if ($exception instanceof ChoiceNameInterface) {
                $exception->setChoiceName($choice->getName());
            }
            throw $exception;
        }

        return $result;
    }
}
