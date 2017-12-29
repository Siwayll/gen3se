<?php

namespace Gen3se\Engine;

/**
 * Class Engine
 * @package Gen3se\Engine
 */
class Engine
{
    protected $choiceProvider;
    protected $scenario;
    protected $dataExporter;

    public function __construct(
        ChoiceProviderInterface $choiceProvider,
        ScenarioInterface $scenario,
        DataExporterInterface $dataExporter
    ) {
        $this->choiceProvider = $choiceProvider;
        $this->scenario = $scenario;
        $this->dataExporter = $dataExporter;
    }

    public function run()
    {
        while ($this->scenario->hasNext()) {
            $choice = $this->choiceProvider->get($this->scenario->next());

            $resolver = new Resolver($choice);
            $resultOpt = $resolver->getPickedOption();

            $this->dataExporter->saveFor($choice, $resultOpt);
        }

        return $this;
    }

    public function exportResult()
    {
        return $this->dataExporter;
    }
}
