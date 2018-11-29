<?php declare(strict_types = 1);

namespace Gen3se\Engine\Basic;

use Gen3se\Engine\Choice\Exporter\Result;
use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Exception\Option\AlreadyPresent;
use Gen3se\Engine\Exception\Option\NotFound;
use Gen3se\Engine\Exception\Option\NotFoundInStack;
use Gen3se\Engine\Exception\Option\PositionMustBeRelevent;
use Gen3se\Engine\Randomizer;

final class Panel implements \Countable, \Gen3se\Engine\Choice\Panel
{
    /** @var Option[] */
    private $container = [];

    /** @var ?Option */
    private $pickedOption = null;

    /**
     * Create a collection of Options
     */
    public function __construct(Option ...$options)
    {
        foreach ($options as $option) {
            $option->signUpTo($this);
        }
    }

    public function exportResult(Result $exporter): void
    {
        if ($this->pickedOption === null) {
            return;
        }

        $exporter->addResult($this->pickedOption);
    }

    /**
     * Clone all the Option in the container
     */
    public function __clone()
    {
        $clonedContainer = $this->container;
        $this->container = [];
        /** @var Option $option */
        foreach ($clonedContainer as $option) {
            $option = clone $option;
            $option->signUpTo($this);
        }
    }

    // initiateNewPanel() ?
    public function copy(): \Gen3se\Engine\Choice\Panel
    {
        $this->pickedOption = null;
        return clone $this;
    }

    /**
     * @deprecated on doit pouvoir retirer $optionId
     */
    public function addOption(string $optionId, Option $option): void
    {
        if (isset($this->container[$optionId])) {
            throw new AlreadyPresent($optionId);
        }
        $this->container[$optionId] = $option;
    }

    /**
     * @deprecated
     */
    public function get(string $optionName): ?Option
    {
        if (!isset($this->container[$optionName])) {
            throw new NotFound($optionName);
        }
        return $this->container[$optionName] ?? null;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->container);
    }

    private function getTotalWeight(): int
    {
        $total = 0;
        \array_walk($this->container, function (Option $option) use (&$total) {
            $option->incrementOfWeight($total);
        });

        return $total;
    }

    /**
     * Iterate on each option with height > 0
     */
    private function eachSelectableOption(): \Generator
    {
        foreach ($this->container as $option) {
            if (!$option->isSelectable()) {
                continue;
            }

            yield $option;
        }
    }

    public function selectAnOption(Randomizer $randomizer): void
    {
        $target = $randomizer->rollForRange($this->getTotalWeight());
        if ($target < 0) {
            return;
        }
        $cursor = 0;
        foreach ($this->eachSelectableOption() as $option) {
            $option->incrementOfWeight($cursor);
            if ($cursor >= $target) {
                $this->pickedOption = $option;
                // replace Option by SelectedOption in Options
                break;
            }
        }
    }

    /**
     * Iterates over options list
     * @deprecated
     */
    public function each(): \Generator
    {
        foreach ($this->container as $option) {
            yield $option;
        }
    }
}
