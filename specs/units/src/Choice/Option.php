<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Choice\Option\Data\Text;
use Gen3se\Engine\Choice\Option\Data\Simple;
use Gen3se\Engine\Specs\Units\Provider\OptionDataTrait;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Option extends Test
{
    use OptionDataTrait;

    public function shouldHaveAName()
    {
        $this
            ->exception(function () {
                $this->newTestedInstance('', 0);
            })
                ->hasMessage('Option must have a non-empty name')
                ->hasCode(400)
            ->exception(function () {
                $this->newTestedInstance([], 10);
            })
                ->isInstanceOf('\TypeError')
            ->given(
                $optionName = 'opt-1',
                $this->newTestedInstance($optionName, 100)
            )
            ->string($this->testedInstance->getName())
                ->isEqualTo($optionName)
        ;
    }

    public function shouldHaveWeightGreaterThanZero()
    {
        $this
            ->exception(function () {
                $this->newTestedInstance('plop', new \stdClass());
            })
            ->isInstanceOf('\TypeError')
            ->KapowException(function () {
                $this->newTestedInstance('badOption', -100);
            })
                ->hasMessage('Option {optionName} must have a weight greater than zero')
                ->hasKapowMessage('Option badOption must have a weight greater than zero')
                ->hasCode(Level::ERROR)

            ->given($this->newTestedInstance('opt-1', 500))
            ->KapowException(function () {
                $this->testedInstance->setWeight(-1);
            })
                ->hasKapowMessage('Option opt-1 must have a weight greater than zero')
                ->hasCode(Level::ERROR)
        ;
    }

    public function shouldGetWeight()
    {
        $this
            ->given(
                $weight = 0,
                $newWeight = 255,
                $this->newTestedInstance('opt-1', $weight)
            )
            ->integer($this->testedInstance->getWeight())
                ->isEqualTo($weight)

            ->if($this->testedInstance->setWeight($newWeight))
            ->integer($this->testedInstance->getWeight())
                ->isEqualTo($newWeight)
        ;
    }

    public function shouldAcceptData()
    {
        $this
            ->given(
                $this->newTestedInstance('name-1', 300),
                $mockData = $this->createMockOptionData()
            )
            ->object($this->testedInstance->add($mockData))
                ->isTestedInstance()
        ;
    }

    public function shouldBeCapableToExportData()
    {
        $this
            ->given($this->newTestedInstance('name-1', 300))
            ->object(
                $this->testedInstance
                    ->add(new Text('Lorem ipsum set dolor'))
                    ->add(new Simple('custom', 'value'))
                    ->add(new Simple('data.toto', 1))
            )
                ->isTestedInstance()
            ->array($this->testedInstance->dataToArray())
                ->notHasKeys(['name', 'weight'])
                ->string['text']->isEqualTo('Lorem ipsum set dolor')
                ->string['custom']->isEqualTo('value')
                ->integer['data.toto']->isEqualTo(1)
        ;
    }

    public function shouldBeCapableToExportMultipleSameTypeData()
    {
        $this
            ->given(
                ($this->newTestedInstance('name-1', 300))
                    ->add(new Text('text one'))
                    ->add(new Text('text two'))
            )
            ->array($this->testedInstance->dataToArray())
                ->notHasKeys(['name', 'weight'])
                ->array['text']->isEqualTo([
                    'text one',
                    'text two'
                ])
        ;
    }
}
