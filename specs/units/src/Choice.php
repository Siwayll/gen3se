<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Data;
use Gen3se\Engine\Choice\Name;
use Gen3se\Engine\Specs\Units\Core\Exception\ExceptionWithChoiceName;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Data as MockChoiceDataProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Panel as PanelMockProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Randomize as MockRandomizeProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Step as MockStepProvider;
use Gen3se\Engine\Specs\Units\Core\Test;
use Gen3se\Engine\Step\PostResolve;
use Gen3se\Engine\Step\Prepare;
use Gen3se\Engine\Step\Resolve;
use Siwayll\Kapow\Level;

abstract class Choice extends Test
{
    use PanelMockProvider;
    use MockRandomizeProvider;
    use MockStepProvider;
    use MockChoiceDataProvider;
    use MockChoiceProvider;

    /**
     * @tags AngryPath
     */
    public function shouldBeAChoice(): void
    {
        $this
            ->testedClass
                ->hasInterface(\Gen3se\Engine\Choice::class)
        ;
    }

    /**
     * @tags AngryPath
     */
    public function shouldNotAcceptEmptyPanel(): void
    {
        $this
            ->KapowException(function () {
                $this->newTestedInstance($this->createNewNameMock('EmptyPanel'), $this->createPanelMock(0));
            })
                ->hasMessage('Choice {choiceName} must have a non-empty collection of Option')
                ->hasKapowMessage('Choice EmptyPanel must have a non-empty collection of Option')
                ->hasCode(Level::ERROR)
        ;
    }

    // not sure if it's use again
    public function shouldBeClonable(): void
    {
        $this->skip('not use');
        $this
            ->given(
                $collection = $this->createPanelMock(1),
                $this->newTestedInstance('choice', $collection)
            )
            ->if($clone = clone $this->testedInstance)
            ->object($clone)
                ->isCloneOf($this->testedInstance)
        ;
    }

    public function shouldTreatsAllStepGiven()
    {
        $this->skip('rework in progress');
        $this
            ->given(
                $collection = $this->createPanelMock(1),
                $this->newTestedInstance('choice', $collection),
                $stepOne = $this->createMockStep(
                    Prepare::class,
                    $stepOneCallable = function () use (&$stepOneArguments) {
                        $stepOneArguments = \func_get_args();
                    }
                ),
                $stepTwo = $this->createMockStep(
                    Prepare::class,
                    $stepTwoCallable = function () {
                    }
                )
            )
            ->if($this->testedInstance->treatsThis($stepOne, $stepTwo))
            ->mock($stepOne)
                ->call('__invoke')
                    ->once()
            ->mock($stepTwo)
                ->call('__invoke')
                    ->once()
            ->array($stepOneArguments)
                ->object[0]->isCloneOf($this->testedInstance)

        ;
    }

    public function shouldStoreResolveStepData()
    {
        $this->skip('rework in progress');
        $this
            ->given(
                $collection = $this->createPanelMock(1),
                $this->newTestedInstance('choice', $collection),
                $stepOne = $this->createMockStep(
                    Resolve::class,
                    $stepOneCallable = function () use (&$stepOneArguments) {
                        $stepOneArguments = \func_get_args();
                        return $this->createNewMockOfResolvedChoice();
                    }
                )
            )
            ->if($this->testedInstance->treatsThis($stepOne))
            ->mock($stepOne)
                ->call('__invoke')->once()
            ->array($stepOneArguments)
                ->object[0]->isCloneOf($this->testedInstance)
        ;
    }

    public function shouldAcceptData()
    {
        $this->skip('Should be tested via a data contract');
        $this
            ->given(
                $collection = $this->createPanelMock(1),
                $this->newTestedInstance($this->createNewNameMock(), $collection),
                $mockData = $this->createMockChoiceData()
            )
            ->object($this->testedInstance->add($mockData))
                ->isTestedInstance()
        ;
    }

    public function shouldFindDataByInterfaceName()
    {
        $this->skip('Should be tested via a data contract');
        $this
            ->given(
                $collection = $this->createPanelMock(1),
                $mockData = $this->createMockChoiceData(),
                ($this->newTestedInstance('choice', $collection))
                    ->add($mockData)
            )
            ->generator($this->testedInstance->findData('foo'))
                ->isEmpty()
            ->generator($this->testedInstance->findData(Data::class))
                ->hasSize(1)
            ->generator($this->testedInstance->findData(Data::class))
                ->yields->object->isEqualTo($mockData)
        ;
    }

    public function shouldBeResolvable()
    {
        $this
            ->given(
                $panelCopy = $this->createPanelMock(5),
                $panel = $this->createPanelMock(5, $panelCopy),
                $this->newTestedInstance($this->createNewNameMock(), $panel),
                $randomize = $this->createNewMockOfRandomize()
            )
            ->if($this->testedInstance->resolve($randomize))
            ->mock($panel)
                ->call('copy')->once()
                ->call('selectAnOption')->withIdenticalArguments($randomize)->never()
            ->mock($panelCopy)
                ->call('selectAnOption')->withIdenticalArguments($randomize)->once()
        ;
    }

    public function shouldAddChoiceNameToExceptionIfNeeded()
    {
        $this
            ->given(
                $panelCopy = $this->createPanelMock(5),
                $exceptionWithChoiceName = $this->newMockInstance(ExceptionWithChoiceName::class),
                $this->calling($panelCopy)->selectAnOption->throw = $exceptionWithChoiceName,
                $panel = $this->createPanelMock(5, $panelCopy),
                $choiceName = 'Error Choice',
                $this->newTestedInstance($this->createNewNameMock($choiceName), $panel),
                $randomize = $this->createNewMockOfRandomize()
            )
            ->KapowException(function () use ($randomize) {
                $this->testedInstance->resolve($randomize);
            })
            ->mock($exceptionWithChoiceName)
                ->call('setChoiceName')->once()
        ;
    }

    private function createNewNameMock(?string $value = null)
    {
        if ($value === null) {
            $value = \uniqid('name_');
        }
        $name = $this->newMockInstance(Name::class);
        $name->getMockController()->__toString = $value;

        return $name;
    }
}
