<?php declare(strict_types = 1);

namespace Gen3se\Engine;

interface Choice
{
//    public function getName(): string;
//
//    // @deprecated
//    public function getOptionCollection(): OptionCollection;

    public function signUpTo(Bible $bible): void;

    /**
     * Resolve the choice and convert them to a result
     */
    public function resolve(Randomizer $randomize, ?Step ...$step): void;
    // @todo résoudre un choix initialise un nouveau Panel d'options — en y
    // appliquant les steps — et l'enregistre

    // @todo change name to ResultExporter
    public function exportResult(\Gen3se\Engine\Choice\Exporter\Result $exporter): void;
    /**
     * Treats all the Steps with a clone of the Choice
     * @todo merge with resolve ?
     */
//    public function treatsThis(Step ...$step): void;

//    /**
//     * Add custom Data to the Choice
//     * @todo move to a ~Data interface (it's not needed in Choice process)
//     */
//    public function add(Data $data): Choice;
//
//    /**
//     * Find all Data who implement the interface $interfaceName
//     * @todo move to a ~Data interface (it's not needed in Choice process)
//     */
//    public function findData($interfaceName);
}
