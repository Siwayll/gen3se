<?php

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Option extends Test
{
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

    public function shouldAcceptCustomFields()
    {
        $this
            ->given($this->newTestedInstance('name-1', 300))
            ->object($this->testedInstance->set('custom1', 'value'))
                ->isTestedInstance()
            ->string($this->testedInstance->get('custom1'))
                ->isEqualTo('value')
            ->boolean($this->testedInstance->exists('custom1'))
                ->isTrue()
            ->boolean($this->testedInstance->exists('customField'))
                ->isFalse()
        ;
    }

    public function shouldSaveDataToClean()
    {
        $this
            ->given($this->newTestedInstance('name-1', 300))
            ->object(
                $this->testedInstance
                    ->set('text', 'Lorem ipsum set dolor')
                    ->set('custom', 'value')
                    ->set('data.toto', 1)
            )
                ->isTestedInstance()
            ->object($this->testedInstance->cleanField('data.toto'))
                ->isTestedInstance()
            ->object($this->testedInstance->cleanField('unpresentField'))
                ->isTestedInstance()
            ->array($this->testedInstance->exportCleanFields())
                ->notHasKey('data.toto')
        ;
    }

    public function shouldBeCapableToExportCleanData()
    {
        $this
            ->given($this->newTestedInstance('name-1', 300))
            ->object(
                $this->testedInstance
                    ->set('text', 'Lorem ipsum set dolor')
                    ->set('custom', 'value')
                    ->set('data.toto', 1)
            )
                ->isTestedInstance()
            ->array($this->testedInstance->exportCleanFields())
                ->notHasKeys(['name', 'weight'])
                ->string['text']->isEqualTo('Lorem ipsum set dolor')
                ->string['custom']->isEqualTo('value')
                ->integer['data.toto']->isEqualTo(1)
        ;
    }

    public function shouldNotAcceptToBreakMandatoryData()
    {
        $this
            ->given(
                $name = 'name-1',
                $weight = 300,
                $this->newTestedInstance($name, $weight)
            )
            ->KapowException(function () {
                $this->testedInstance->set('name', 'newName');
            })
                ->hasKapowMessage('Option '.$name.' cannot change its name')
                ->hasCode(Level::ERROR)
            ->exception(function () {
                $this->testedInstance->set('weight', 'toto');
            })
                ->isInstanceOf('\TypeError')
        ;
    }
}
