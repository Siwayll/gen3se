<?php

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Resolver;
use Gen3se\Engine\Exception\Engine\InstructionAlreadyPresent;
use Gen3se\Engine\Mod\InstructionInterface;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Mod\NeedChoiceProviderInterface;
use Gen3se\Engine\Mod\NeedScenarioInterface;
use Gen3se\Engine\Option\Option;

/**
 * Resolution Engine of Gen3se
 */
class Engine
{
    protected $choiceProvider;
    protected $scenario;
    protected $dataExporter;

    /**
     * List of mods enabled for the engine
     */
    protected $modList = [];

    protected $instructions = [];

    public function __construct(
        ChoiceProviderInterface $choiceProvider,
        ScenarioInterface $scenario,
        DataExporterInterface $dataExporter
    ) {
        $this->choiceProvider = $choiceProvider;
        $this->scenario = $scenario;
        $this->dataExporter = $dataExporter;
    }

    /**
     * add a mod to the engine
     */
    public function addMod(ModInterface $mod): self
    {
        if ($mod instanceof NeedScenarioInterface) {
            $mod->setScenario($this->scenario);
        }

        if ($mod instanceof NeedChoiceProviderInterface) {
            $mod->setChoiceProvider($this->choiceProvider);
        }

        foreach ($mod->getInstructions() as $instruction) {
            /** @var $instruction InstructionInterface */
            if (!$instruction instanceof InstructionInterface) {
                throw new \TypeError(
                    'Argument 1 passed to ' . __METHOD__ . '() must be of the type ModInterface, '
                    . gettype($instruction) . ' given'
                );
            }
            if (isset($this->instructions[$instruction->getCode()])) {
                throw new InstructionAlreadyPresent($instruction->getCode());
            }
            $this->instructions[$instruction->getCode()] = $instruction;
        }
        return $this;
    }

    /**
     * Resolve all choices in the Scenario
     */
    public function run(): self
    {
        while ($this->scenario->hasNext()) {
            $choice = $this->choiceProvider->get($this->scenario->next());

            $resolver = new Resolver($choice);
            $resultOpt = $resolver->getPickedOption();

            $this->executeModInstructions($resultOpt);

            $this->dataExporter->saveFor($choice, $resultOpt);
        }

        return $this;
    }

    private function executeModInstructions(Option $option): self
    {
        $fields = $option->exportCleanFields();
        foreach ($fields as $instructionCode => $value) {
            if (!isset($this->instructions[$instructionCode])) {
                continue;
            }
            /** @var $instruction InstructionInterface */
            $instruction = $this->instructions[$instructionCode];
            if ($instruction->validate($value)) {
                $instruction($value);
                $option->cleanField($instruction->getCode());
            }
        }

        return $this;
    }

    public function exportResult()
    {
        return $this->dataExporter;
    }
}
