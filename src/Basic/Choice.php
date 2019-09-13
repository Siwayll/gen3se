<?php declare(strict_types = 1);

namespace Gen3se\Engine\Basic;

use Gen3se\Engine\Bible;
use Gen3se\Engine\Choice as ChoiceInterface;
use Gen3se\Engine\Choice\Name as ChoiceName;
use Gen3se\Engine\Data;
use Gen3se\Engine\Exception\Choice\MustHaveNonEmptyCollectionOfOptions;
use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Randomizer;
use Gen3se\Engine\Step;

class Choice implements ChoiceInterface
{
    /** @var ChoiceName */
    private $name;

    /** @var ChoiceInterface\Panel[] */
    protected $panels;

    /**
     * Specific data of the Choice
     */
    protected $data = [];

    public function __construct(ChoiceName $choiceName, ChoiceInterface\Panel $panel)
    {
        $this->name = $choiceName;

        if (\count($panel) === 0) {
            throw new MustHaveNonEmptyCollectionOfOptions((string) $this->name);
        }

        $this->panels[0] = $panel;
    }

    public function signUpTo(Bible $bible): void
    {
        $bible->addChoice((string) $this->name, $this);
    }

    /**
     * Add custom Data to the Choice
     */
    public function add(Data $data): void
    {
        $this->data[] = $data;
    }
//
//    /**
//     * Get all the Data who implement $interfaceName
//     */
//    public function findData(string $interfaceName): \Generator
//    {
//        foreach ($this->data as $data) {
//            if (\in_array($interfaceName, \class_implements($data))) {
//                yield $data;
//            }
//        }
//    }

    private function extractMethodName(string $stage): string
    {
        return \lcfirst(\substr($stage, \strrpos($stage, '\\') + 1));
    }

    private function applySteps($steps, string $stage, ...$arguments): void
    {
        foreach ($steps as $step) {
            if (!\in_array($stage, \class_implements($step))) {
                continue;
            }
            $step->{$this->extractMethodName($stage)}(...$arguments);
//            $step(...$arguments);
        }
    }

    public function resolve(Randomizer $randomize, ?Step ...$steps): void
    {
        try {
            // alway initiat a Panel from the originel Panel
            // to avoid already altered Option
            $panel = $this->panels[0]->copy();
            $this->applySteps(
                $steps,
                Step\Prepare::class,
                [
                    $panel,
                    // add a clone of all precedents Panels (read Only)
                ]
            );
            $panel->selectAnOption($randomize);
            $this->applySteps(
                $steps,
                Step\PostResolve::class,
                $panel
            );
            $this->panels[] = $panel;
        } catch (ChoiceNameInterface $exception) {
            $exception->setChoiceName((string) $this->name);
            throw $exception;
        }
    }

    public function exportResult(ChoiceInterface\Exporter\Result $exporter): void
    {
        $exporter->setChoiceName($this->name);
        $exporter->setData($this->data);
        \array_walk($this->panels, function (ChoiceInterface\Panel $panel) use ($exporter) {
            $panel->exportResult($exporter);
        });
    }
}
