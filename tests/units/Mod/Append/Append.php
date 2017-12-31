<?php

namespace Gen3se\Engine\Specs\Units\Mod\Append;

use Gen3se\Engine\Scenario;
use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;
use Siwayll\Kapow\Level;

class Append extends Test
{
    use SimpleChoiceTrait;

    public function shouldImplementModInterface()
    {
        $this
            ->given($this->newTestedInstance)
            ->testedClass
                ->hasInterface('Gen3se\Engine\Mod\ModInterface')
        ;
    }

    public function shouldSayItSDependances()
    {
        $this
            ->given($this->newTestedInstance)
            ->testedClass
                ->hasInterface('Gen3se\Engine\Mod\NeedChoiceProviderInterface')
                ->hasInterface('Gen3se\Engine\Mod\NeedScenarioInterface')
        ;
    }

    public function shouldGiveTheAddAtEndInstruction()
    {
        $this
            ->given($this->newTestedInstance())
            ->testedClass
                ->hasConstant('INSTRUCTION')
            ->string($this->testedInstance::INSTRUCTION)
                ->isEqualTo('scenario.append')
            ->array($this->testedInstance->getInstructions())
                ->size->isEqualTo(1)
            ->class(get_class($this->testedInstance->getInstructions()[0]))
                ->hasInterface('Gen3se\Engine\Mod\InstructionInterface')
            ->string($this->testedInstance->getInstructions()[0]->getCode())
                ->isEqualTo($this->testedInstance::INSTRUCTION)
        ;
    }

    public function shouldOnlyAcceptChoiceNameAsInstructionData()
    {
        $this
            ->given(
                $this->newTestedInstance(),
                $choiceName = $this->getEyeColorChoice()->getName(),
                $this->testedInstance->setChoiceProvider($this->getProviderWithSimpleChoices())
            )
            ->boolean($this->testedInstance->dataValidator($choiceName))
                ->isTrue()
            ->exception(function () {
                $this->testedInstance->dataValidator(new \stdClass());
            })
                ->isInstanceOf('\TypeError')
            ->KapowException(
                function () {
                    $this->testedInstance->dataValidator('notFoundChoice');
                }
            )
                ->hasMessage('Choice "{choiceName}" not found')
                ->hasKapowMessage('Choice "notFoundChoice" not found')
                ->hasCode(Level::ERROR)
            ->KapowException(
                function () {
                    $this->testedInstance->dataValidator('');
                }
            )
                ->hasMessage('Choice "{choiceName}" not found')
                ->hasKapowMessage('Choice "" not found')
                ->hasCode(Level::ERROR)
        ;
    }

    public function shouldAddChoiceAtTheEndOfTheSenario()
    {
        $this
            ->given(
                $this->newTestedInstance(),
                $scenario = new Scenario(),
                $choiceName = $this->getEyeColorChoice()->getName(),
                $this->testedInstance->setChoiceProvider($this->getProviderWithSimpleChoices()),
                $this->testedInstance->setScenario($scenario)
            )
            ->boolean($scenario->hasNext())
                ->isFalse()
            ->if($this->testedInstance->run($choiceName))
            ->boolean($scenario->hasNext())
                ->isTrue()
            ->string($scenario->next())
                ->isEqualTo($choiceName)
            ->boolean($scenario->hasNext())
                ->isFalse()
        ;
    }
}