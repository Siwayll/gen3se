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
}
