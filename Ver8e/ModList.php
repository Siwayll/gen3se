<?php
/**
 * Created by PhpStorm.
 * User: siwayll
 * Date: 18/12/16
 * Time: 11:06
 */

namespace Siwayll\Gen3se\Ver8e;

use Hoa\Visitor\Element;

class ModList
{
    private $list = [];

    public function __construct(Element $globalElement)
    {
        foreach ($globalElement->getChildren() as $element) {
            $data = $element->getValue();
            $this->addToList($data['value']);
        }
    }

    public function addToList(string $modName): ModList
    {
        $this->list[] = 'Siwayll\Gen3se\Modificator\\' . $modName;
        return $this;
    }

    public function getList(): array
    {
        return $this->list;
    }

}