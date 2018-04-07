<?php

namespace Gen3se\Engine\Mod\Append;

use Gen3se\Engine\ChoiceProviderInterface;
use Gen3se\Engine\Exception\Choice\NotFound;
use Gen3se\Engine\Mod\Instruction;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Mod\NeedChoiceProviderInterface;
use Gen3se\Engine\Mod\NeedScenarioInterface;
use Gen3se\Engine\ScenarioInterface;

/**
 * Mod who add "addAtTheEnd" instruction to Option
 */
class Append implements ModInterface, NeedChoiceProviderInterface, NeedScenarioInterface
{
    const INSTRUCTION = 'scenario.append';

    /**
     * @var ChoiceProviderInterface
     */
    private $provider;

    /**
     * @var ScenarioInterface
     */
    private $scenario;

    /**
     * Send the list of instructions specific to the Mod
     */
    public function getInstructions(): array
    {
        return [
            new Instruction(
                self::INSTRUCTION,
                [$this, 'dataValidator'],
                [$this, 'run']
            )
        ];
    }

    public function setChoiceProvider(ChoiceProviderInterface $provider): NeedChoiceProviderInterface
    {
        $this->provider = $provider;

        return $this;
    }

    public function setScenario(ScenarioInterface $scenario): NeedScenarioInterface
    {
        $this->scenario = $scenario;
        return $this;
    }

    /**
     * Check if $value is an real choiceName
     * # Exceptions
     * if $value is not a string choiceName
     */
    public function dataValidator($value): bool
    {
        $choiceNames = $this->formatValue($value, __METHOD__);

        foreach ($choiceNames as $choiceName) {
            if (!$this->provider->hasChoice($choiceName)) {
                throw new NotFound($choiceName);
            }
        }

        return true;
    }

    private function formatValue($value, string $method): array
    {
        if (!is_string($value) && !is_array($value)) {
            throw new \TypeError(
                'Argument 1 passed to ' . $method . '() must be of the type string or array of strings, '
                . gettype($value) . ' given'
            );
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        return $value;
    }

    /**
     * Add the choiceName at the end of the current scenario
     */
    public function run($value)
    {
        $choiceNames = $this->formatValue($value, __METHOD__);

        foreach ($choiceNames as $choiceName) {
            $this->scenario->append($choiceName);
        }
    }
}