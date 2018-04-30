<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step\PostResolve;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Result;
use Gen3se\Engine\Step;

class Fil implements Step\PostResolve
{
    private $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    public function __invoke(Choice $choice, Choice\Option $option): void
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
}
