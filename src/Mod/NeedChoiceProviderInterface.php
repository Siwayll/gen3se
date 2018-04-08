<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod;

use Gen3se\Engine\ChoiceProviderInterface;

interface NeedChoiceProviderInterface
{
    /**
     * register the current Provider in the mod
     */
    public function setChoiceProvider(ChoiceProviderInterface $provider): NeedChoiceProviderInterface;
}
