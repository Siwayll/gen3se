<?php declare(strict_types = 1);

namespace Gen3se\Engine\Scenario;

use Gen3se\Engine\Scenario;

/**
 * List of Choices names to solve
 */
class Simple implements Scenario
{
    private $list = [];

    public function __construct(?string ...$choiceName)
    {
        foreach ($choiceName as $name) {
            $this->append($name);
        }
    }

    /**
     * Add a Choice name to the end of the Scenario
     */
    public function append(string $choiceName): void
    {
        $this->list[] = $choiceName;
    }


    public function read(callable $runOnEachChoiceName): void
    {
        foreach ($this->list as $choiceName) {
            $runOnEachChoiceName($choiceName);
        }
    }
}
