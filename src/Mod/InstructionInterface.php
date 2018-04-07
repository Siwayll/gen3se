<?php

namespace Gen3se\Engine\Mod;

interface InstructionInterface
{
    /**
     * Return the code identifiying the Instruction
     */
    public function getCode(): string;

    /**
     * Validates data associated with the Instruction
     */
    public function validate($value): bool;

    /**
     * Run the instruction
     */
    public function __invoke($value);
}
