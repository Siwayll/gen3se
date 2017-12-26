<?php

namespace Gen3se\Engine\Specs\Units\Option;

use Gen3se\Engine\Tests\Units\Test;

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
        ;
    }

    public function shouldAcceptCustomFields()
    {
        $this
            ->given($option = $this->newTestedInstance('name-1', 300))
            ->if($option->customField = 'foo')
            ->string($option->customField)
                ->isEqualTo('foo')
        ;
    }

    public function shouldBeManipulatedAsAnArray()
    {
        $this
            ->given(
                $name = 'name-1',
                $weight = 300,
                $option = $this->newTestedInstance($name, $weight)
            )
            ->class(get_class($option))
                ->hasInterface('\ArrayAccess')
            ->boolean(isset($option['name']))
                ->isTrue()
            ->boolean(isset($option['falseName']))
                ->isFalse()
            ->string($option['name'])
                ->isEqualTo($name)
            ->integer($option['weight'])
                ->isEqualTo($weight)

            ->given($option['customField'] = 'foo')
            ->string($option['customField'])
                ->isEqualTo('foo')


            ->exception(function () {
                unset($this->testedInstance['name']);
            })
                ->hasMessage('Option ' . $name . ' cant unset andatory data')
                ->hasCode(400)
        ;
    }
}
