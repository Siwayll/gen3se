<?php

namespace Gen3se\Engine\Specs\Units\Step;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Mod\Collection as ModCollection;
use Gen3se\Engine\Specs\Units\Provider\ModCollectionTrait;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Prepare extends Test
{
    use SimpleChoiceTrait, ModCollectionTrait;

    protected function choiceProvider()
    {
        return [
            $this->getEyeColorChoice(),
            $this->getHairColorChoice()
        ];
    }

    public function shouldHaveConstantWithItsName()
    {
        $this
            ->testedClass
                ->hasConstant('STEP_NAME')
        ;
    }

    /**
     * @dataProvider choiceProvider
     */
    public function shouldCloneAChoice(Choice $choice)
    {
        $this
            ->given(
                $modCollection = new ModCollection(),
                $option = $choice->getOptionCollection()->findByPositonInStack(1)
            )
            ->object($this->newTestedInstance($choice, $modCollection))
            ->object(call_user_func($this->testedInstance))
                ->isInstanceOf('Gen3se\Engine\Choice\Choice')
                ->isCloneOf($choice)
            ->string(call_user_func($this->testedInstance)->getName())
                ->isEqualTo($choice->getName())
            ->object(call_user_func($this->testedInstance)->getOptionCollection())
                ->isCloneOf($choice->getOptionCollection())
            ->object(call_user_func($this->testedInstance)->getOptionCollection()->get($option->getName()))
                ->isCloneOf($option)
        ;
    }

    protected function createMockModPrepareReady()
    {
        $mock = new \mock\Gen3se\Engine\Step\IsPrepareReady();
        $mock->getMockController()->isUpForStep = function ($name) {
            if ($name === \Gen3se\Engine\Step\Prepare::STEP_NAME) {
                return true;
            }
            return false;
        };
        return $mock;
    }

    public function shouldExecuteMod()
    {
        $this
            ->given(
                $choice = $this->getEyeColorChoice(),
                $modCollection = new ModCollection(),
                $modMock = $this->createMockModStepable('>prepare'),
                $modCollection->add($modMock)
            )

            ->KapowException(
                function () use ($choice, $modCollection) {
                    $this->newTestedInstance($choice, $modCollection);
                }
            )
                ->hasMessage('Mod {modClass} is not made for Prepare step')
                ->hasCode(Level::ERROR)
            ->mock($modMock)
                ->call('isUpForStep')->once()

            ->given(
                $modCollection = new ModCollection(),
                $modMock = $this->createMockModPrepareReady(),
                $modCollection->add($modMock)
            )
            ->if($this->newTestedInstance($choice, $modCollection))
            ->mock($modMock)
                ->call('isUpForStep')->once()
                ->call('execPrepare')->once()
        ;
    }
}
