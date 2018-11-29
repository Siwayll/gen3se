<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Basic;

use Gen3se\Engine\Choice\Option\Data;
use Gen3se\Engine\Choice\Option\Data\Simple as SimpleData;
use Gen3se\Engine\Choice\Option\Data\Text;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Option\Data as OptionDataProvider;
use Gen3se\Engine\Specs\Units\Core\Test;
use Siwayll\Kapow\Level;

class Option extends Test
{
    use OptionDataProvider;

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

    public function shouldInformIfOptionIsSelectable(): void
    {
        $this
            ->given(
                $this->newTestedInstance('opt-1', 0)
            )
            ->boolean($this->testedInstance->isSelectable())
                ->isFalse()

            ->if($this->testedInstance->setWeight(255))
            ->boolean($this->testedInstance->isSelectable())
                ->isTrue()
        ;
    }

    public function shouldIncrementGivenWeight(): void
    {
        $this
            ->given(
                $weight = 100,
                $this->newTestedInstance('opt-1', 50)
            )
            ->if($this->testedInstance->incrementOfWeight($weight))
            ->integer($weight)
                ->isEqualTo(150)
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
                    ->add(new SimpleData('custom', 'value'))
                    ->add(new SimpleData('data.toto', 1))
            )
                ->isTestedInstance()
            ->array($this->testedInstance->dataToArray())
                ->notHasKeys(['name', 'weight'])
                ->string['text']->isEqualTo('name-1')
                ->string['custom']->isEqualTo('value')
                ->integer['data.toto']->isEqualTo(1)
        ;
    }

    public function shouldFindDataByInterfaceName()
    {
        $this
            ->given(
                $simpleTextMock = $this->createMockOptionData('simple text'),
                ($this->newTestedInstance('name', 100))
                    ->add($simpleTextMock)
            )
            ->generator($this->testedInstance->findData('foo'))
                ->isEmpty()
            ->generator($this->testedInstance->findData(Data::class))
                ->hasSize(2)
            ->generator($this->testedInstance->findData(Data::class))
                ->yields->object
                ->yields->object->isEqualTo($simpleTextMock)
        ;
    }

    public function shouldBeCapableToExportMultipleSameTypeData()
    {
        $this
            ->given(
                ($this->newTestedInstance('text one', 300))
                    ->add(new Text('text two'))
            )
            ->array($this->testedInstance->dataToArray())
                ->notHasKeys(['name', 'weight'])
                ->array['text']->isEqualTo([
                    'text one',
                    'text two',
                ])
        ;
    }
}
