<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Scenario as Scenario;
use Siwayll\RumData\RumData;

interface Bible
{
    public function play(Scenario $scenario): RumData;
}
