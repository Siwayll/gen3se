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
        foreach ($globalElement->getChildren() as $element) {
            switch ($element->getId()) {
                case '#choice' :
                    $loader->addChoice($element);
                    break;

                case '#scenario' :

                    $scenarioName = $element->getChild(0)->getValue()['value'];
                    $scenarioList = [];

                    foreach ($element->getChildren() as $subElement) {
                        if ($subElement->getId() === '#scenarioRender') {
                            $render = $subElement->getChild(0)->getValue()['value'];
                            continue;
                        }
                        if ($subElement->getId() != '#scenarioChoice') {
                            continue;
                        }

                        $scenarioList[] = $subElement->getChild(0)->getValue()['value'];
                    }
                    break;
            }
        }

        return new Generator($scenarioName, $loader, $scenarioList, $render);
    }
}