<?php

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\OptionInterface;
use Gen3se\Engine\Choice\Resolver;
use Gen3se\Engine\Exception\Engine\InstructionAlreadyPresent;
use Gen3se\Engine\Mod\Collection as ModCollection;
use Gen3se\Engine\Mod\InstructionInterface;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Mod\NeedChoiceProviderInterface;
use Gen3se\Engine\Mod\NeedScenarioInterface;
use Gen3se\Engine\Step\Prepare;

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
    protected $modList;

    /**
     * List of custom instructions
     */
    protected $instructions = [];

    public function __construct(
        ChoiceProviderInterface $choiceProvider,
        ScenarioInterface $scenario,
        DataExporterInterface $dataExporter
    ) {
        $this->modList = new ModCollection();
        $this->choiceProvider = $choiceProvider;
        $this->scenario = $scenario;
        $this->dataExporter = $dataExporter;
    }

    /**
     * Add a mod to the engine
     * Give access to Scenario or ChoiceProvider if needed
     * Extracts Instructions and add them to the instructionList
     */
    public function addMod(ModInterface $mod): self
    {
        if ($mod instanceof NeedScenarioInterface) {
            $mod->setScenario($this->scenario);
        }

        if ($mod instanceof NeedChoiceProviderInterface) {
            $mod->setChoiceProvider($this->choiceProvider);
        }

        $this
            ->saveInstructions($mod->getInstructions())
            ->modList->add($mod)
        ;

        return $this;
    }

    /**
     * Save new instructions in the custom instructions list
     *
     * #exception
     * if the array $instructions does not contains only Instruction
     *
     * if the code of an instruction is already in use
     */
    private function saveInstructions(array $instructions): self
    {
        foreach ($instructions as $instruction) {
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
     * Resolve the main scenario
     */
    public function run(): self
    {
        return $this->resolve($this->scenario);
    }

    /**
     * Resolve all choices given by the specified scenario
     */
    private function resolve(ScenarioInterface $scenario): self
    {
        while ($scenario->hasNext()) {
            // get the Choice requested by the Scenario
            $choice = $this->choiceProvider->get($scenario->next());
            // first main step : Choice preparation
            $prepareStep = new Prepare($choice, $this->modList);

            // Choice resolution
            $resolver = new Resolver($prepareStep());
            unset($prepareStep);

            // Get all data in the selected Option
            $resultOpt = $resolver->getPickedOption();

            // Post resolution step
            $this->executeModInstructions($resultOpt);

            // Data save
            $this->dataExporter->saveFor($choice, $resultOpt);
        }

        // @todo #16 add post resolution step

        return $this;
    }

    /**
     * Run Mods given by the instructions of the option
     */
    private function executeModInstructions(OptionInterface $option): self
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
