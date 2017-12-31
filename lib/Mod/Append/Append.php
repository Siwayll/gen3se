<?php

namespace Gen3se\Engine\Mod\Append;

use Gen3se\Engine\ChoiceProviderInterface;
use Gen3se\Engine\Exception\Choice\NotFound;
use Gen3se\Engine\Mod\Instruction;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Mod\NeedProviderInterface;
use Gen3se\Engine\Mod\NeedScenarioInterface;
use Gen3se\Engine\ScenarioInterface;

/**
 * Mod who add "addAtTheEnd" instruction to Option
 */
class Append implements ModInterface, NeedProviderInterface, NeedScenarioInterface
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
    public function getInstructions()
    {
        return [
            new Instruction(
                self::INSTRUCTION,
                [$this, 'dataValidator'],
                [$this, 'run']
            )
        ];
    }

    public function setProvider(ChoiceProviderInterface $provider): NeedProviderInterface
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
    public function dataValidator(string $value): bool
    {
        if (!$this->provider->hasChoice($value)) {
            throw new NotFound($value);
        }
        return true;
    }

    /**
     * Add the choiceName at the end of the current scenario
     */
    public function run(string $choiceName)
    {
        $this->scenario->append($choiceName);
    }
}
