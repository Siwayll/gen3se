<?php

namespace Gen3se\Engine\Specs\Units\Mod;

use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;

class Instruction extends Test
{
    use SimpleChoiceTrait;

    public function shouldImplementInstructionInterface()
    {
        $this
            ->testedClass
                ->hasInterface('Gen3se\Engine\Mod\InstructionInterface')
        ;
    }

    public function shouldHaveACode()
    {
        $this
            ->given(
                $code = 'instructionCode',
                $validator = function () {
                },
                $runner = function () {
                },
                $this->newTestedInstance($code, $validator, $runner)
            )
            ->string($this->testedInstance->getCode())
                ->isEqualTo($code)
        ;
    }

    public function shouldHasAValidatorForControlsData()
    {
        $this
            ->given(
                $code = 'instructionCode',
                $validator = function ($value) {
                    if ($value === 'foo') {
                        return true;
                    }
                    return false;
                },
                $runner = function () {
                },
                $this->newTestedInstance($code, $validator, $runner)
            )
            ->boolean($this->testedInstance->validate('foo'))
                ->isTrue()
            ->boolean($this->testedInstance->validate('bar'))
                ->isFalse()
        ;
    }

    public function shouldBeCallableToRunInstruction()
    {
        $this
            ->given(
                $code = 'instructionCode',
                $validator = function () {
                },
                $runner = function ($value) {
                    if ($value === 'goodValue') {
                        return 'IRunMyInstruction';
                    }
                    return 'iNotRunInstruction';
                },
                $this->newTestedInstance($code, $validator, $runner)
            )
            ->object($this->testedInstance)
                ->isCallable()
            ->string(call_user_func($this->testedInstance, 'goodValue'))
                ->isEqualTo('IRunMyInstruction')
            ->string(call_user_func($this->testedInstance, 'badValue'))
                ->isEqualTo('iNotRunInstruction')
        ;
    }
}
