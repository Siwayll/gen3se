<?php declare(strict_types = 1);

namespace Gen3se\Engine\Basic;

use Gen3se\Engine\Rand;
use Gen3se\Engine\Scenario as ScenarioInterface;
use Gen3se\Engine\Scenario\ScenarioExporter;

/**
 * List of Choices names to solve
 * @todo find another name
 */
class Scenario implements ScenarioInterface
{
    /** @var array List of Choice Name to process */
    private $choiceNames = [];

    /** @var array Result of the Scenario */
    private $results = [];

    private $randomize;

    public function __construct(?string ...$choiceName)
    {
        $this->randomize = new Rand();
        foreach ($choiceName as $name) {
            $this->append((string) $name);
        }
    }

    /**
     * Add a Choice name to the end of the Scenario
     */
    public function append(string $choiceName): self
    {
        $this->choiceNames[] = $choiceName;

        return $this;
    }


    public function read(callable $reader): ScenarioInterface
    {
        foreach ($this->choiceNames as $choiceName) {
            // @todo pass Result to the reader ?
            $this->results[] = $reader($choiceName, $this->randomize);
        }

        return $this;
    }

    public function export(ScenarioExporter $exporter): ScenarioInterface
    {
        \array_map(function ($key, $value) use ($exporter) {
            $exporter->addResult($key, $value);
        }, $this->results);

        return $this;
    }
}
