<?php

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Provider;
use Gen3se\Engine\Mod\Append\Append;
use Gen3se\Engine\Tests\Units\Provider\AppendChoiceTrait;
use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;
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
            ->array($this->testedInstance->exportResult()->get($eyeChoice->getName()))
            ->array($this->testedInstance->exportResult()->get($hairChoire->getName()))
        ;
    }

    public function shouldRegistersMods()
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
                ->hasKapowMessage('Instruction "' . Append::INSTRUCTION . '" is already present')
                ->hasCode(Level::ERROR)
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
            ->array($this->testedInstance->exportResult()->get($eyeArchChoice->getName()))
                ->hasKey('text')
                ->notHasKey(Append::INSTRUCTION)
            ->array($this->testedInstance->exportResult()->get($eyeChoice->getName()))
                ->size->isGreaterThan(0)
        ;
    }
}
