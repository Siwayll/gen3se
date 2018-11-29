<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Scenario\ScenarioExporter;

interface Scenario
{
    /**
     * Read the Scenario and run the closure for each choice name in it
     */
    public function read(callable $reader): Scenario;

    /**
     * Send Result data to the Exporter
     */
    public function export(ScenarioExporter $exporter): Scenario;
}
