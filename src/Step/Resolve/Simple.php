<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step\Resolve;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Rand;
use Gen3se\Engine\Result;
use Gen3se\Engine\Step;

class Simple implements Step\Resolve
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

    public function __invoke(Choice $choice): Choice\Option
    {
        $choiceOptions = $choice->getOptionCollection();
        try {
            $rand = new Rand(0, $choiceOptions->getTotalWeight());
            $rand->roll();
            $result = $choiceOptions->findByPositionInStack($rand->getResult());
            $this->fillData($choice, $result);
        } catch (\Siwayll\Kapow\Exception $exception) {
            if ($exception instanceof ChoiceNameInterface) {
                $exception->setChoiceName($choice->getName());
            }
            throw $exception;
        }

        return $result;
    }
}
