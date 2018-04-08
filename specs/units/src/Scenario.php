<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;

class Scenario extends Test
{
    public function shouldImplementScenarioInterface()
    {
        $this
            ->given($this->newTestedInstance)
            ->class(get_class($this->testedInstance))
                ->hasInterface('Gen3se\Engine\ScenarioInterface')
        ;
    }

    public function shouldManageAListOfChoiceNames()
    {
        $this
            ->given(
                $choiceList = [
                    'choix-1',
                    'choix-2',
                    'choix-3',
                    'choix-4'
                ],
                $this->newTestedInstance()
            )
            ->object(
                $this->testedInstance
                    ->append($choiceList[0])
                    ->append($choiceList[1])
                    ->append($choiceList[2])
                    ->append($choiceList[3])
            )
                ->isTestedInstance()

            ->boolean($this->testedInstance->hasNext())
                ->isTrue()
            ->string($this->testedInstance->next())
                ->isEqualTo($choiceList[0])
            ->string($this->testedInstance->next())
                ->isEqualTo($choiceList[1])
            ->string($this->testedInstance->next())
                ->isEqualTo($choiceList[2])
            ->string($this->testedInstance->next())
                ->isEqualTo($choiceList[3])
            ->boolean($this->testedInstance->hasNext())
                ->isFalse()
        ;
    }

    public function shouldBeCountable()
    {
        $this
            ->given(
                $choiceList = [
                    'choix-1',
                    'choix-2',
                    'choix-3',
                    'choix-4'
                ],
                $this->newTestedInstance()
            )
            ->object(
                $this->testedInstance
                    ->append($choiceList[0])
                    ->append($choiceList[1])
            )
                ->isTestedInstance()
            ->integer(count($this->testedInstance))
                ->isEqualTo(2)
            ->object(
                $this->testedInstance
                    ->append($choiceList[2])
                    ->append($choiceList[2])
            )
                ->isTestedInstance()
            ->integer(count($this->testedInstance))
                ->isEqualTo(4)
        ;
    }
}
