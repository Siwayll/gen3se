<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;
use Gen3se\Engine\Choice;
use Siwayll\Kapow\Level;

class Provider extends Test
{
    use SimpleChoiceTrait;

    protected function choiceProvider()
    {
        return [
            $this->getEyeColorChoice(),
            $this->getHairColorChoice()
        ];
    }

    /**
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
                    $this->testedInstance->get('notFoundChoice');
                }
            )
                ->hasMessage('Choice "{choiceName}" not found')
                ->hasKapowMessage('Choice "notFoundChoice" not found')
                ->hasCode(Level::ERROR)
        ;
    }

    public function shouldAddSayIfAChoiceNameExist()
    {
        $this
            ->given(
                $choice = $this->getEyeColorChoice(),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($choice))
            ->boolean($this->testedInstance->hasChoice($choice->getName()))
                ->isTrue()
            ->boolean($this->testedInstance->hasChoice('notFound'))
                ->isFalse()
        ;
    }
}
