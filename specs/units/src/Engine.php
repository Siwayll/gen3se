<?php

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Provider;
use Gen3se\Engine\Mod\Append\Append;
use Gen3se\Engine\Mod\Append\DataInterface;
use Gen3se\Engine\Mod\Instruction;
use Gen3se\Engine\Specs\Units\Provider\AppendChoiceTrait;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;
use Gen3se\Engine\Scenario;
use Gen3se\Engine\DataExporter;
use Siwayll\Kapow\Level;

class Engine extends Test
{
    use SimpleChoiceTrait, AppendChoiceTrait;

    public function shouldResolveASimpleScenario()
    {
        $this
            ->given(
                $eyeChoice = $this->getEyeColorChoice(),
                $hairChoire = $this->getHairColorChoice(),
                $choiceProvider = $this->getProviderWithSimpleChoices(),
                $scenario = new Scenario(),
                $scenario->append($eyeChoice->getName()),
                $scenario->append($eyeChoice->getName()),
                $scenario->append($hairChoire->getName()),
                $dataExporter = new DataExporter(),
                $this->newTestedInstance($choiceProvider, $scenario, $dataExporter)
            )
            ->object($this->testedInstance->run())
                ->isTestedInstance()
            ->object($this->testedInstance->exportResult()->get($eyeChoice->getName()))
            ->object($this->testedInstance->exportResult()->get($hairChoire->getName()))
        ;
    }

    public function shouldRegistersModsAndInstructions()
    {
        $this
            ->given(
                $eyeArchChoice = $this->getEyeArchChoice(),
                $choiceProvider = $this->getProviderWithAppendModChoices(),
                $scenario = new Scenario(),
                $scenario->append($eyeArchChoice->getName()),
                $dataExporter = new DataExporter(),
                $this->newTestedInstance($choiceProvider, $scenario, $dataExporter),
                $appendMod = new Append()
            )
            ->object($this->testedInstance->addMod($appendMod))
                ->isTestedInstance()
            ->exception(function () {
                $this->testedInstance->addMod(new \stdClass());
            })
                ->isInstanceOf('\TypeError')
            ->exception(function () {
                $this->testedInstance->addMod('');
            })
                ->isInstanceOf('\TypeError')
            ->KapowException(function () use ($appendMod) {
                $this->testedInstance->addMod($appendMod);
            })
                ->hasMessage('Instruction "{newInstructionCode}" is already present')
                ->hasKapowMessage('Instruction "' . DataInterface::class . '" is already present')
                ->hasCode(Level::ERROR)
            ->if(
                $badMod = new \mock\Gen3se\Engine\Mod\ModInterface(),
                $badMod->getMockController()->getInstructions = function () {
                    return [new \stdClass()];
                }
            )
            ->exception(function () use ($badMod) {
                $this->testedInstance->addMod($badMod);
            })
                ->isInstanceOf('\TypeError')
        ;
    }

    public function shouldGiveModAccessToScenarioIfNecessary()
    {
        $this
            ->given(
                $scenario = new Scenario(),
                $scenario->append($this->getEyeArchChoice()->getName()),
                $this->newTestedInstance($this->getProviderWithAppendModChoices(), $scenario, new DataExporter()),
                $appendMod = new \mock\Gen3se\Engine\Mod\Append\Append(),
                $appendMod->getMockController()->getInstructions = function () {
                    return [];
                }
            )
            ->object($this->testedInstance->addMod($appendMod))
                ->isTestedInstance()
            ->mock($appendMod)
                ->call('setScenario')
                    ->withIdenticalArguments($scenario)->once()
        ;
    }

    public function shouldGiveModAccessToChoiceProviderIfNecessary()
    {
        $this
            ->given(
                $scenario = new Scenario(),
                $scenario->append($this->getEyeArchChoice()->getName()),
                $choiceProvider = $this->getProviderWithAppendModChoices(),
                $this->newTestedInstance($choiceProvider, $scenario, new DataExporter()),
                $appendMod = new \mock\Gen3se\Engine\Mod\Append\Append(),
                $appendMod->getMockController()->getInstructions = function () {
                    return [];
                }
            )
            ->object($this->testedInstance->addMod($appendMod))
                ->isTestedInstance()
            ->mock($appendMod)
                ->call('setChoiceProvider')
                    ->withIdenticalArguments($choiceProvider)->once()
        ;
    }

    public function shouldRunModInstructions()
    {
        $this
            ->given(
                $eyeArchChoice = $this->getEyeArchChoice(),
                $eyeChoice = $this->getEyeColorChoice(),
                $choiceProvider = $this->getProviderWithAppendModChoices(),
                $scenario = new Scenario(),
                $scenario->append($eyeArchChoice->getName()),
                $dataExporter = new DataExporter(),
                $this->newTestedInstance($choiceProvider, $scenario, $dataExporter),
                $appendMod = new Append()
            )
            ->if($this->testedInstance->addMod($appendMod))
            ->and($this->testedInstance->run())
            ->dump($this->testedInstance->exportResult())
            ->array((array) $this->testedInstance->exportResult()->get($eyeArchChoice->getName()))
                ->hasKey('text')
                ->notHasKey(DataInterface::class)
            ->array((array) $this->testedInstance->exportResult()->get($eyeChoice->getName()))
                ->size->isGreaterThan(0)
        ;
    }
}
