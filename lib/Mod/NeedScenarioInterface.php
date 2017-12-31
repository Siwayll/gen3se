<?php

namespace Gen3se\Engine\Mod;

use Gen3se\Engine\ScenarioInterface;

interface NeedScenarioInterface
{
    /**
     * registrer the current scenario in the mod
     */
    public function setScenario(ScenarioInterface $scenario): NeedScenarioInterface;
}
