<?php

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;
use Gen3se\Engine\Choice\Choice;
use Siwayll\Kapow\Level;

class Provider extends Test
{
    use SimpleChoiceTrait;

    protected function choiceProvider()
    {
        return [
            $this->getEyeColorChoice()
        ];
    }

    /**
     * @param Choice $choice
     * @dataProvider choiceProvider
     */
    public function shouldBeCapableOfAddAChoice(Choice $choice)
    {
        $this
            ->given($this->newTestedInstance())
            ->object($this->testedInstance->add($choice))
                ->isTestedInstance()
        ;
    }

    /**
     * @param Choice $choice
     * @dataProvider choiceProvider
     */
    public function shouldReturnChoiceByName(Choice $choice)
    {
        $this
            ->given(
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($choice))
            ->object($this->testedInstance->get($choice->getName()))
                ->isIdenticalTo($choice)
            ->KapowException(
                function () {
                    $this->testedInstance->get('<notFoundChoice>');
                }
            )
                ->hasMessage('Choice {choiceName} not found')
                ->hasKapowMessage('Choice <notFoundChoice> not found')
                ->hasCode(Level::ERROR)
        ;
    }
}
