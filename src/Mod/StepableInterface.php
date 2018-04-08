<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod;

interface StepableInterface extends ModInterface
{
    public function isUpForStep(string $stepName): bool;
}
