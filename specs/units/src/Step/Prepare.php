<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Step;

use Gen3se\Engine\Mod\Collection as ModCollection;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\ModCollectionTrait;
use Gen3se\Engine\Specs\Units\Core\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Core\Test;
use Siwayll\Kapow\Level;

class Prepare extends Test
{
    use SimpleChoiceTrait;
    use ModCollectionTrait;

    use MockChoiceProvider;

    protected function choiceProvider()
    {
        return [
            $this->getEyeColorChoice(),
            $this->getHairColorChoice(),
        ];
    }

    public function shouldHaveConstantWithItsName()
    {
        $this
            ->testedClass
                ->hasConstant('STEP_NAME')
        ;
    }

    public function shouldCloneAChoice()
    {
        $this
            ->given(
                $choice = $this->createMockChoice(),
                $modCollection = new ModCollection()
            )
            ->object($this->newTestedInstance($choice, $modCollection))
            ->object(\call_user_func($this->testedInstance))
                ->isCloneOf($choice)
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
