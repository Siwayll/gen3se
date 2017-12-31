<?php

namespace Gen3se\Engine\Mod;

interface ModInterface
{
    /**
     * Return a list of InstructionInterface
     */
    public function getInstructions(): array;
}
