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
            ->given(
                $optionName = 'opt-1',
                $this->newTestedInstance($optionName, 100)
            )
            ->string($this->testedInstance->getName())
                ->isEqualTo($optionName)
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
            ->assert('it should test if field exists normaly')
                ->boolean(isset($option['name']))
                    ->isTrue()
                ->boolean(isset($option['falseName']))
                    ->isFalse()

            ->assert('it should return data normaly')
                ->string($option['name'])
                    ->isEqualTo($name)
                ->integer($option['weight'])
                    ->isEqualTo($weight)
                ->variable($option['falsField'])
                    ->isNull()

            ->assert('it should agree to create a new field')
                ->given($option['customField'] = 'foo')
                ->string($option['customField'])
                    ->isEqualTo('foo')
                 ->boolean(isset($option['customField']))
                    ->isTrue()

            ->assert('it should unset data normaly')
                ->given($option['customField'] = 'foo')
                ->boolean(isset($option['customField']))
                    ->isTrue()
                ->when(
                    function () use ($option) {
                        unset($option['customField']);
                    }
                )
                ->boolean(isset($option['customField']))
                    ->isFalse()


            ->assert('it shouldn\'t accept to break mandatory data')
                ->exception(function () {
                    $this->testedInstance['name'] = '';
                })
                    ->hasMessage('Option must have a non-empty name')
                    ->hasCode(400)
                ->KapowException(function () {
                    unset($this->testedInstance['name']);
                })
                    ->hasMessage('Option {optionName} cant unset mandatory data')
                    ->hasKapowMessage('Option name-1 cant unset mandatory data')
                    ->hasCode(400)
        ;
    }
}
