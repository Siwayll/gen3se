<?php

namespace Gen3se\Engine\Choice;

// @todo simplify this !
interface OptionInterface
{
    public function getName(): string;
    public function getWeight(): int;
    public function setWeight(int $value): OptionInterface;
    public function set(string $name, $value): OptionInterface;
    public function get(string $name);
    public function exists(string $name): bool;
    public function exportCleanFields(): array;
    public function cleanField(string $fieldName): OptionInterface;
}
