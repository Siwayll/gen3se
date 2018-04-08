<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod;

/**
 * Simple Instruction
 */
class Instruction implements InstructionInterface
{
    /**
     * Code identifying the Instruction
     */
    private $code;

    /**
     * Callable for validate data associated with the code
     */
    private $validator;

    /**
     * Callable who run the instruction
     */
    private $runner;

    public function __construct(string $code, callable $validator, callable $runner)
    {
        $this->code = $code;
        $this->validator = $validator;
        $this->runner = $runner;
    }

    public function __invoke($value)
    {
        return \call_user_func($this->runner, $value);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function validate($value): bool
    {
        return \call_user_func($this->validator, $value);
    }
}
