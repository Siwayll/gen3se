<?php

namespace Siwayll\Gen3se\Ver8e;

use Hoa\Visitor\Element;
use Hoa\Visitor\Visit;
use Siwayll\Gen3se\Generator\Generic as Generator;
use Siwayll\Gen3se\Loader\FromCompiler as Loader;

class Parser implements Visit
{
    /**
     * @param Element $globalElement
     * @param null    $handle
     * @param null    $eldnah
     * @return Generator
     */
    public function visit(Element $globalElement, &$handle = null, $eldnah = null)
    {
        $loader = new Loader();
        $render = null;
        $modList = null;
        foreach ($globalElement->getChildren() as $element) {
            switch ($element->getId()) {
                case '#choice' :
                    $loader->addChoice($element);
                    break;

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

        return new Generator($scenarioName, $loader, $scenarioList, $render, $modList);
    }
}