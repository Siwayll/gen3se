<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Features;

use Gen3se\Engine\Choice\Exporter\Result;
use Gen3se\Engine\Choice\Name;
use Gen3se\Engine\Choice\Option;

class AssertResultExporter implements Result
{
    private $data = [];
    private $options = [];
    private $choiceName = '';

    public function addResult(Option $option): void
    {
        $this->options[] = $option;
    }

    public function setChoiceName(Name $choiceName): void
    {
        $this->choiceName = $choiceName;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    // test purpose

    public function getChoiceNameAsString(): string
    {
        return (string) $this->choiceName;
    }

    public function getOptionsData(): array
    {
        $result = [];
        \array_walk($this->options, function (Option $option) use (&$result) {
            $result[] = $option->dataToArray();
        });

        return $result;
    }

    public function countOptions(): int
    {
        return \count($this->options);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
