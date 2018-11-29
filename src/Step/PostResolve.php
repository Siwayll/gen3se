<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step;

use Gen3se\Engine\Choice\Panel;
use Gen3se\Engine\Step;

interface PostResolve extends Step
{
    public function postResolve(Panel $panel): void;
}
