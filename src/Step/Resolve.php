<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Rand;
use Gen3se\Engine\Result;
use Gen3se\Engine\Step;

class Resolve implements Step
{
    private $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    private function fillData(Choice $choice, Choice\Option $option): void
    {
        $isRegister = false;
        foreach ($choice->findData(Result\Filer::class) as $filer) {
            $this->result->registersTo($option, $filer);
            $isRegister = true;
        }

        if ($isRegister === true) {
            return;
        }

        $this->result->registersTo($option, new Choice\Data\Fil($choice->getName()));
    }

    public function __invoke(Choice $choice): void
    {
        $choiceOptions = $choice->getOptionCollection();
        try {
            $rand = new Rand(0, $choiceOptions->getTotalWeight());
            $rand->roll();
            $this->fillData($choice, $choiceOptions->findByPositionInStack($rand->getResult()));
        } catch (\Siwayll\Kapow\Exception $exception) {
            if ($exception instanceof ChoiceNameInterface) {
                $exception->setChoiceName($choice->getName());
            }
            throw $exception;
        }
    }
}
