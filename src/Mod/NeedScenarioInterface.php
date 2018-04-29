<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod;

use Gen3se\Engine\Scenario;

interface NeedScenarioInterface
{
    /**
     * registrer the current scenario in the mod
     */
    public function setScenario(Scenario $scenario): NeedScenarioInterface;
}
