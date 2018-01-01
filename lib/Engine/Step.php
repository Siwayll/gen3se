<?php

namespace Gen3se\Engine\Engine;


use Gen3se\Engine\Mod\Collection as ModCollection;

interface Step
{
    public function getStepName(): string;

    public function execStep(ModCollection $modCollection);
}