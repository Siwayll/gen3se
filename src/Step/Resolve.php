<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Rand;
use Gen3se\Engine\Step;

class Resolve implements Step
{
    public function __invoke(Choice $choice): void
    {
        $choiceOptions = $choice->getOptionCollection();
        $rand = new Rand(0, $choiceOptions->getTotalWeight());
        $rand->roll();
        try {
            $choiceOptions->findByPositionInStack($rand->getResult());
        } catch (\Siwayll\Kapow\Exception $exception) {
            if ($exception instanceof ChoiceNameInterface) {
                $exception->setChoiceName($choice->getName());
            }
            throw $exception;
        }
    }
}
