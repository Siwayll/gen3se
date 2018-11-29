<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Choice\Exporter\Result;
use Gen3se\Engine\Randomizer;

interface Panel extends \Countable
{
    public function addOption(string $optionId, Option $option): void;

    public function selectAnOption(Randomizer $randomizer): void;

    public function exportResult(Result $exporter): void;

    /**
     * Create a new copy of the Panel to work in.
     * (basically do a clone of the Panel)
     */
    public function copy(): Panel;
}
