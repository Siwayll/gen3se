<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Step;

interface PostResolve extends Step
{
    public function __invoke(Choice $choice, ?Option $option = null): void;
}
