<?php
/**
 * Created by PhpStorm.
 * User: siwayll
 * Date: 15/08/17
 * Time: 08:52
 */

namespace Gen3se\Engine\Ver8e;


use Hoa\Compiler\Llk\TreeNode;

class ChoiceParser
{
    const FIELD_NAME = 'name';
    const FIELD_OPTIONS = 'options';
    const FIELD_STORAGE = 'storageRule';

    private $formatedData = [
        self::FIELD_NAME => null,
        self::FIELD_OPTIONS => []
    ];

    private $optionGlobal = [];

    public function __construct(TreeNode $choiceNode)
    {
        foreach ($choiceNode->getChildren() as $element) {
            switch ($element->getId()) {
                case 'token':
                    $this->parseToken($element);
                    break;
                case '#choiceStorage' :
                    $this->parseStorage($element);
                    break;
                case '#choiceOption' :
                    $option = new ChoiceOptionParser($element, $this->optionGlobal);
                    $this->formatedData[self::FIELD_OPTIONS][] = $option->get();
                    unset($option);
                    break;
                case '#choiceElement':
                    $this->parseGlobal($element);
                    break;
                case '#choiceRules':
                    $this->parseRules($element);
                    break;
                default:
                    var_dump($element->getId());
            }
        }
    }

    public function get()
    {
        return $this->formatedData;
    }

    public function getName(): string
    {
        return $this->formatedData[self::FIELD_NAME];
    }

    public function debugGetGlobal()
    {
        return $this->optionGlobal;
    }

    private function standardDataValue(TreeNode $dataValue, $defaultKey = 'text')
    {
        $return = [
            'key' => $defaultKey,
            'value' => null,
        ];
        $elements = $dataValue->getChildren();
        $target = 0;
        if (count($elements) == 2) {
            $return['key'] = $elements[0]->getValueValue();
            $target = 1;
        }

        if (isset($elements[$target])) {
            $data = $elements[$target]->getValue();
            $return['value'] = $data['value'];
            if ($data['token'] === 'null') {
                $return['value'] = null;
            }

        }

        return $return;
    }

    /**
     * @param TreeNode $node
     */
    private function parseToken(TreeNode $node)
    {
        $data = $node->getValue();
        switch ($data['token']) {
            case 'name':
                $this->formatedData[self::FIELD_NAME] = $data['value'];
                break;
            default:
                var_dump($data);
        }
    }

    /**
     * Parse des règles de stockage du resultat
     *
     * @param TreeNode $node
     * @return void
     */
    private function parseStorage(TreeNode $node)
    {
        $storageRule = [];
        foreach ($node->getChildren() as $subElement) {
            if ($subElement->getId() != 'token') {
                continue;
            }

            $storageRule[] = $subElement->getValueValue();
        }
        $this->formatedData[self::FIELD_STORAGE] = $storageRule;
    }

    /**
     * @param TreeNode $node
     */
    private function parseGlobal(TreeNode $node)
    {
        $value = $this->standardDataValue($node, 'global');
        $this->optionGlobal[$value['key']] = $value['value'];
    }

    private function parseRules(TreeNode $node)
    {
        $elmtChildren = $node->getChildren();
        $name = 'defaultNameOption';
        $value = '';
        foreach ($elmtChildren as $subElement) {
            $children = $subElement->getChildren();
            foreach ($children as $child) {
                $values = $child->getValue();
                switch ($values['token']) {
                    case 'name':
                        $name = $values['value'];
                        break;
                    case 'value':
                        $value = $values['value'];
                        break;
                }
            }
        }
        $this->formatedData[$name] = $value;
    }
}