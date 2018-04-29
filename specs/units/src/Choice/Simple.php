<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Specs\Units\Provider\Choice\Collection as MockOptionCollectionProvider;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Simple extends Test
{
    use MockOptionCollectionProvider;

    protected function collectionProvider()
    {
        return [
            $this->createMockOptionCollection(1),
        ];
    }

    public function shouldHaveANonEmptyCollectionOfOptions()
    {
        $this
            ->given($collection = $this->createMockOptionCollection(1))
            ->KapowException(function () {
                $this->newTestedInstance('EmptyCollection', $this->createMockOptionCollection(0));
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
        ;
    }

    public function shouldHaveAName()
    {
        $this
            ->given(
                $collection = $this->createMockOptionCollection(1)
            )
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

    public function shouldBeClonable()
    {
        $this
            ->given(
                $collection = $this->createMockOptionCollection(1),
                $this->newTestedInstance('choice', $collection)
            )
            ->if($clone = clone $this->testedInstance)
            ->object($clone)
                ->isCloneOf($this->testedInstance)
            ->object($clone->getOptionCollection())
                ->isCloneOf($collection)
        ;
    }
}
