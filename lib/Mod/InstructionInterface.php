<?php

namespace Gen3se\Engine\Mod;

/**
 * Interface InstructionInterface
 * @package Gen3se\Engine\Mod
 */
interface InstructionInterface
{
    public function getKey(): string;

    public function validate($value): bool;

    public function __invoke();
}
