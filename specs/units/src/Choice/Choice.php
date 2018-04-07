<?php

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Specs\Units\Test;
use Gen3se\Engine\Choice\Option\Collection as CollectionOfOptions;
use Siwayll\Kapow\Level;

class Choice extends Test
{
    protected function collectionProvider()
    {
        $collection = new CollectionOfOptions();
        $collection->add(new Option('opt-1', 100));
        $collection->add(new Option('opt-2', 200));

        return [
            $collection
        ];
    }

    /**
     * @dataProvider collectionProvider
     */
    public function shouldHaveANonEmptyCollectionOfOptions(CollectionOfOptions $collection)
    {
        $this
            ->KapowException(function () {
                $collection = new CollectionOfOptions();
                $this->newTestedInstance('EmptyCollection', $collection);
            })
                ->hasMessage('Choice {choiceName} must have a non-empty collection of Option')
                ->hasKapowMessage('Choice EmptyCollection must have a non-empty collection of Option')
                ->hasCode(Level::ERROR)
            ->given(
                $name = 'choice',
                $this->newTestedInstance($name, $collection)
            )
            ->object($this->testedInstance->getOptionCollection())
                ->isIdenticalTo($collection)
            ->object($this->testedInstance->getOptionCollection()->get('opt-1'))
                ->isIdenticalTo($collection->get('opt-1'))
        ;
    }

    /**
     * @dataProvider collectionProvider
     */
    public function shouldHaveAName(CollectionOfOptions $collection)
    {
        $this
            ->exception(function () use ($collection) {
                $this->newTestedInstance('', $collection);
            })
                ->hasMessage('Choice must have a non-empty name')
                ->hasCode(Level::ERROR)
            ->given(
                $name = 'choice',
                $this->newTestedInstance($name, $collection)
            )
            ->string($this->testedInstance->getName())
                ->isEqualTo($name)
        ;
    }

    /**
     * @dataProvider collectionProvider
     */
    public function shouldBeClonable(CollectionOfOptions $collection)
    {
        $this
            ->given(
                $this->newTestedInstance('choice', $collection),
                $clone = clone $this->testedInstance
            )
            ->object($clone)
                ->isCloneOf($this->testedInstance)
            ->object($clone->getOptionCollection())
                ->isCloneOf($collection)
        ;
    }

    /**
     * @dataProvider collectionProvider
     */
    public function shouldAcceptCustomFields(CollectionOfOptions $collection)
    {
        $this
            ->given($this->newTestedInstance('choice', $collection))
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

    /**
     * @dataProvider collectionProvider
     */
    public function shouldNotAcceptToBreakMandatoryData(CollectionOfOptions $collection)
    {
        $this
            ->given(
                $name = 'name-1',
                $this->newTestedInstance($name, $collection)
            )
            ->KapowException(function () {
                $this->testedInstance->set('name', 'newName');
            })
                ->hasKapowMessage('Choice '.$name.' cannot change its name')
                ->hasCode(Level::ERROR)
        ;
    }
}
