<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Scenario;

use Gen3se\Engine\Scenario as ScenarioInterface;
use Gen3se\Engine\Specs\Units\Core\Test;

class Simple extends Test
{
    public function shouldImplementScenarioInterface()
    {
        $this
            ->testedClass
                ->hasInterface(ScenarioInterface::class)
        ;
    }

    public function shouldIterateOnEachChoiceName()
    {
        $this
            ->given(
                $choiceList = [
                    'choix-1',
                    'choix-2',
                    'choix-3',
                    'choix-4',
                ],
                $this->newTestedInstance(...$choiceList)
            )
            ->if($this->testedInstance->read(
                $callable = function () use (&$arguments) {
                    $arguments[] = \func_get_args();
                }
            ))
            ->array($arguments)
                ->hasSize(4)
                ->array[0]->isEqualTo([$choiceList[0]])
                ->array[1]->isEqualTo([$choiceList[1]])
                ->array[2]->isEqualTo([$choiceList[2]])
                ->array[3]->isEqualTo([$choiceList[3]])

        ;
    }
}
