<?php

namespace Gen3se\Engine\Ver8e;

use Hoa\Visitor\Element;
use Hoa\Visitor\Visit;
use Gen3se\Engine\Generator\Generic as Generator;
use Gen3se\Engine\Loader\FromCompiler as Loader;

class Parser implements Visit
{
    private $loader;

    public function __construct()
    {
        $this->loader = new Loader();
    }

    public function compileChoices(Element $globalElement)
    {
        foreach ($globalElement->getChildren() as $element) {
            if ($element->getId() !== '#choice') {
                continue;
            }
            $choice = new ChoiceParser($element);
            $this->loader->addChoice($choice->get());
        }
    }

    /**
     * @param Element $globalElement
     * @param null    $handle
     * @param null    $eldnah
     * @return Generator
     */
    public function visit(Element $globalElement, &$handle = null, $eldnah = null)
    {

        $render = null;
        $modList = null;

        $this->compileChoices($globalElement);
        foreach ($globalElement->getChildren() as $element) {
            switch ($element->getId()) {

                case '#scenario' :

                    $scenarioName = $element->getChild(0)->getValue()['value'];
                    $scenarioList = [];

                    foreach ($element->getChildren() as $subElement) {
                        switch ($subElement->getId()) {
                            case '#scenarioRender':
                                $render = $subElement->getChild(0)->getValue()['value'];
                                break;

                            case '#scenarioMod':
                                $modList = new ModList($subElement);
                                break;
                            case '#scenarioChoice':
                                $iterationNumber = 1;
                                $idElement = 0;


                                if ($subElement->getChild($idElement)->getId() === '#scenarioChoiceMultiplicator') {
                                    $iterationNumber = (int) $subElement->getChild($idElement)->getChild(0)->getValue()['value'];
                                    $idElement++;
                                }

                                $name = $subElement->getChild($idElement)->getValue()['value'];

                                // Application
                                for ($i = 0; $i < $iterationNumber; $i++) {
                                    $scenarioList[] = $name;
                                }

                                break;
                        }
                    }
                    break;
            }
        }

        return new Generator($scenarioName, $this->loader, $scenarioList, $render, $modList);
    }
}