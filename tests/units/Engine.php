<?php

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Provider;
use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;
use Gen3se\Engine\Scenario;
use Gen3se\Engine\DataExporter;

class Engine extends Test
{
    use SimpleChoiceTrait;


    public function shouldResolveASimpleScenario()
    {
        $this
            ->given(
                $eyeChoice = $this->getEyeColorChoice(),
                $hairChoire = $this->getHairColorChoice(),
                $choiceProvider = new Provider(),
                $choiceProvider->add($hairChoire),
                $choiceProvider->add($eyeChoice),
                $scenario = new Scenario(),
                $scenario->append($eyeChoice->getName()),
                $scenario->append($eyeChoice->getName()),
                $scenario->append($hairChoire->getName()),
                $dataExporter = new DataExporter(),
                $this->newTestedInstance($choiceProvider, $scenario, $dataExporter)
            )
            ->object($this->testedInstance->run())
                ->isTestedInstance()
            ->dump($this->testedInstance->exportResult())
            ->array($this->testedInstance->exportResult()->get($eyeChoice->getName()))
            ->array($this->testedInstance->exportResult()->get($hairChoire->getName()))
        ;
    }
}
