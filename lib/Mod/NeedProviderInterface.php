<?php

namespace Gen3se\Engine\Mod;

use Gen3se\Engine\ChoiceProviderInterface;

interface NeedProviderInterface
{
    /**
     * register the current Provider in the mod
     */
    public function setProvider(ChoiceProviderInterface $provider): NeedProviderInterface;
}
