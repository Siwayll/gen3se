<?php declare(strict_types = 1);

namespace Gen3se\Engine;

interface Data
{
    public function registersTo($data, $placement): void;
}
