<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Mod\Append;

use Gen3se\Engine\Mod\Append\DataInterface;
use Gen3se\Engine\Mod\InstructionInterface;
use Gen3se\Engine\Scenario\Simple as Scenario;
use Gen3se\Engine\Specs\Units\Core\Provider\Mod\Append\OptionData;
use Gen3se\Engine\Specs\Units\Core\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Core\Test;
use Siwayll\Kapow\Level;

/**
 * @ignore
 */
class Append extends Test
{
    use OptionData;
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
            ->array($this->testedInstance->getInstructions())
                ->size->isEqualTo(1)
            ->class(\get_class($this->testedInstance->getInstructions()[0]))
                ->hasInterface(InstructionInterface::class)
            ->string($this->testedInstance->getInstructions()[0]->getCode())
                ->isEqualTo(DataInterface::class)
        ;
    }

    public function shouldAcceptAppendDataAsInstructionData()
    {
        $this
            ->given(
                $this->newTestedInstance(),
                $choiceName = $this->createMockAppendData($this->getEyeColorChoice()->getName()),
                $this->testedInstance->setChoiceProvider($this->getProviderWithSimpleChoices()),
                $secondChoiceName = $this->createMockAppendData(
                    $this->getHairColorChoice()->getName(),
                    $this->getEyeColorChoice()->getName()
                )
            )
            ->boolean($this->testedInstance->dataValidator($choiceName))
                ->isTrue()
            ->boolean($this->testedInstance->dataValidator($secondChoiceName))
                ->isTrue()
            ->exception(function () {
                $this->testedInstance->dataValidator(new \stdClass());
            })
                ->isInstanceOf('\TypeError')
            ->KapowException(
                function () {
                    $this->testedInstance->dataValidator($this->createMockAppendData('notFoundChoice'));
                }
            )
                ->hasMessage('Choice "{choiceName}" not found')
                ->hasKapowMessage('Choice "notFoundChoice" not found')
                ->hasCode(Level::ERROR)
            ->KapowException(
                function () {
                    $this->testedInstance->dataValidator($this->createMockAppendData(''));
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
            ->skip('change implementation of Append')
            ->given(
                $this->newTestedInstance(),
                $scenario = new Scenario(),
                $appendData = $this->createMockAppendData($this->getEyeColorChoice()->getName()),
                $this->testedInstance->setChoiceProvider($this->getProviderWithSimpleChoices()),
                $this->testedInstance->setScenario($scenario)
            )
            ->boolean($scenario->hasNext())
                ->isFalse()
            ->if($this->testedInstance->run($appendData))
            ->boolean($scenario->hasNext())
                ->isTrue()
            ->string($scenario->next())
                ->isEqualTo($this->getEyeColorChoice()->getName())
            ->boolean($scenario->hasNext())
                ->isFalse()
        ;
    }

    public function shouldAddAListOfChoicesAtTheEndOfTheScenario()
    {
        $this
            ->skip('change implementation of Append')
            ->given(
                $this->newTestedInstance(),
                $scenario = new Scenario(),
                $appendData = $this->createMockAppendData(
                    $this->getEyeColorChoice()->getName(),
                    $this->getHairColorChoice()->getName()
                ),
                $this->testedInstance->setChoiceProvider($this->getProviderWithSimpleChoices()),
                $this->testedInstance->setScenario($scenario)
            )
            ->boolean($scenario->hasNext())
                ->isFalse()
            ->if($this->testedInstance->run($appendData))
            ->boolean($scenario->hasNext())
                ->isTrue()
            ->string($scenario->next())
                ->isEqualTo($this->getEyeColorChoice()->getName())
            ->string($scenario->next())
                ->isEqualTo($this->getHairColorChoice()->getName())
            ->boolean($scenario->hasNext())
                ->isFalse()
        ;
    }
}
