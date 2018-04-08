<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod;

interface ModInterface
{
    /**
     * Return a list of InstructionInterface
     */
    public function getInstructions(): array;
}
