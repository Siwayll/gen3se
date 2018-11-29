<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step;

use Gen3se\Engine\Choice\Panel;
use Gen3se\Engine\Step;

interface Prepare extends Step
{
    public function prepare(Panel $panel, ?Panel ...$oldPanels): void;
}
