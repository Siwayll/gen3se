<?php declare(strict_types = 1);

namespace Gen3se\Engine\Scenario;

interface ScenarioExporter
{
    public function addResult(string $key, $value): ScenarioExporter;
}
