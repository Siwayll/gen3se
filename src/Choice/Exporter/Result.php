<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Exporter;

use Gen3se\Engine\Choice\Name;
use Gen3se\Engine\Choice\Option;

interface Result
{
    public function setChoiceName(Name $choiceName): void;
    public function addResult(Option $option): void;
    public function setData(array $data): void;
}
