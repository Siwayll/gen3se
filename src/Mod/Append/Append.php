<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Append;

use Gen3se\Engine\ChoiceProviderInterface;
use Gen3se\Engine\Exception\Choice\NotFound;
use Gen3se\Engine\Mod\Instruction;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Mod\NeedChoiceProviderInterface;
use Gen3se\Engine\Mod\NeedScenarioInterface;
use Gen3se\Engine\Mod\Append\DataInterface as AppendInterface;
use Gen3se\Engine\ScenarioInterface;

/**
 * Mod who add "addAtTheEnd" instruction to Option
 */
class Append implements ModInterface, NeedChoiceProviderInterface, NeedScenarioInterface
{
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
                AppendInterface::class,
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
    public function dataValidator(AppendInterface $instruction): bool
    {
        foreach ($instruction->eachChoice() as $choiceName) {
            if (!$this->provider->hasChoice($choiceName)) {
                throw new NotFound($choiceName);
            }
        }

        return true;
    }

    /**
     * Add the choiceName at the end of the current scenario
     */
    public function run(AppendInterface $instruction)
    {
        foreach ($instruction->eachChoice() as $choiceName) {
            $this->scenario->append($choiceName);
        }
    }
}
