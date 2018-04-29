<?php declare(strict_types = 1);

namespace Gen3se\Engine;

interface Step
{
    public function __invoke(Choice $choice): void;
}
