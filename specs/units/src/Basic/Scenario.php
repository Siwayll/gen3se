<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Basic;

use Gen3se\Engine\Scenario as ScenarioInterface;
use Gen3se\Engine\Specs\Units\Core\Test;

class Scenario extends Test
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
                ->child[0](function ($child) use ($choiceList) {
                    $child->contains($choiceList[0]);
                })
                ->child[1](function ($child) use ($choiceList) {
                    $child->contains($choiceList[1]);
                })
                ->child[2](function ($child) use ($choiceList) {
                    $child->contains($choiceList[2]);
                })
                ->child[3](function ($child) use ($choiceList) {
                    $child->contains($choiceList[3]);
                })

        ;
    }
}
