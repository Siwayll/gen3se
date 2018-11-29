<?php declare(strict_types = 1);

namespace Gen3se\Engine\Basic;

use Gen3se\Engine\Bible as BibleInterface;
use Gen3se\Engine\Choice;
use Gen3se\Engine\Choice\Resolved;
use Gen3se\Engine\Rand;
use Gen3se\Engine\Randomizer;
use Gen3se\Engine\Scenario as Scenario;

class Bible implements BibleInterface
{
    private $choiceList = [];

    public function __construct(?Choice ...$choices)
    {
        foreach ($choices as $choice) {
            $choice->signUpTo($this);
        }
    }

    public function addChoice(string $choiceId, Choice $choice): void
    {
        $this->choiceList[$choiceId] = $choice;
    }

    private function findChoice(string $choiceName): Choice
    {
        if (!isset($this->choiceList[$choiceName])) {
            echo 'ok';
        }

        return $this->choiceList[$choiceName];
    }

    public function resolve(string $choiceName, Randomizer $randomizer): void
    {
        $this
            ->findChoice($choiceName)
            ->resolve($randomizer)
        ;
    }

    public function play(Scenario $scenario): void
    {
        $scenario->read([$this, 'resolve']);
    }
}
