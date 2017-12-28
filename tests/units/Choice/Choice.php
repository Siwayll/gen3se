<?php

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Option\Option;
use Gen3se\Engine\Tests\Units\Test;
use Gen3se\Engine\Option\Collection as CollectionOfOptions;

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
     * @param CollectionOfOptions $collection
     * @throws \Gen3se\Engine\Exception\OptionNotFound
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
                ->hasCode(400)
            ->given(
                $name = 'choice',
                $this->newTestedInstance($name, $collection)
            )
            ->object($this->testedInstance->getOptionCollection()->get('opt-1'))
                ->isIdenticalTo($collection->get('opt-1'))
        ;
    }

    /**
     * @param CollectionOfOptions $collection
     * @dataProvider collectionProvider
     */
    public function shouldHaveAName(CollectionOfOptions $collection)
    {
        $this
            ->exception(function () use ($collection) {
                $this->newTestedInstance('', $collection);
            })
            ->hasMessage('Choice must have a non-empty name')
                ->hasCode(400)
            ->given(
                $name = 'choice',
                $this->newTestedInstance($name, $collection)
            )
            ->string($this->testedInstance->getName())
                ->isEqualTo($name)
        ;
    }
}
