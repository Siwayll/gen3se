<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Mod\Tag;

use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Specs\Units\Test;

/**
 * @ignore
 */
class Revision extends Test
{
    public function shouldGetTheRevisionValueAndAWeight()
    {
        $this
            ->object($this->newTestedInstance(0, 0))
                ->isTestedInstance()
                ->isCallable()
            ->object($this->newTestedInstance(10, 500))
                ->isTestedInstance()
            ->object($this->newTestedInstance(1.35, 500))
                ->isTestedInstance()
            ->object($this->newTestedInstance('+500', 500))
                ->isTestedInstance()
            ->object($this->newTestedInstance('-500', 500))
                ->isTestedInstance()
            ->object($this->newTestedInstance('+50.365', 500))
                ->isTestedInstance()

            ->assert('error on weight type\'s is a script error')
                ->exception(
                    function () {
                        $this->newTestedInstance(0, []);
                    }
                )
                    ->isInstanceOf('\TypeError')
                ->exception(
                    function () {
                        $this->newTestedInstance(0, new \stdClass());
                    }
                )
                ->isInstanceOf('\TypeError')

            ->assert('error on revisionValue is an input error')
                ->KapowException(
                    function () {
                        $this->newTestedInstance('+50.3.65', 500);
                    }
                )
                    ->hasMessage('The revision "{revisionValue}" is invalid in {optionName} in {choiceName}')
                    ->hasKapowMessage('The revision "+50.3.65" is invalid in {optionName} in {choiceName}')
                ->KapowException(
                    function () {
                        $this->newTestedInstance([], 200);
                    }
                )
                    ->hasMessage(
                        'Revision value must be of the type string or numeric '
                        . '({varType} given) in {optionName} in {choiceName}'
                    )
                    ->hasKapowMessage(
                        'Revision value must be of the type string or numeric '
                        . '(array given) in {optionName} in {choiceName}'
                    )
                ->KapowException(
                    function () {
                        $this->newTestedInstance(new \stdClass(), 600);
                    }
                )
                    ->hasMessage(
                        'Revision value must be of the type string or numeric '
                        . '({varType} given) in {optionName} in {choiceName}'
                    )
                    ->hasKapowMessage(
                        'Revision value must be of the type string or numeric '
                        . '(object given) in {optionName} in {choiceName}'
                    )
        ;
    }

    public function shouldSimplyMultiplyWeight()
    {
        $this
            ->if($this->newTestedInstance(5, 100))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(500)
            ->if($this->newTestedInstance(.235, 10))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(3)
        ;
    }

    public function shouldAddToWeightIfAsked()
    {
        $this
            ->if($this->newTestedInstance('+5', 100))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(105)
            ->if($this->newTestedInstance('+0.235', 10))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(11)
        ;
    }

    public function shouldSubstractWeightIfAsked()
    {
        $this
            ->if($this->newTestedInstance('-5', 100))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(95)
            ->if($this->newTestedInstance('-1.235', 10))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(9)
            ->if($this->newTestedInstance('-15', 10))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(0)
        ;
    }

    public function shouldAcceptSomeSymboles()
    {
        $this
            ->if($this->newTestedInstance('x5', 100))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(500)
            ->if($this->newTestedInstance('*5', 100))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(500)
            ->if($this->newTestedInstance('15', 10))
            ->integer(\call_user_func($this->testedInstance))
                ->isEqualTo(150)
        ;
    }
}
