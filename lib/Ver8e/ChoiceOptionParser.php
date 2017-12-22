<?php
/**
 * Created by PhpStorm.
 * User: siwayll
 * Date: 15/08/17
 * Time: 08:52
 */

namespace Gen3se\Engine\Ver8e;


use Hoa\Compiler\Llk\TreeNode;

class ChoiceOptionParser
{
    use StandardTrait;

    const UNIQUEID_PREFIX = 'auto_';
    const DEFAULT_WEIGHT = 100;

    private $formatedData = [];

    public function __construct(TreeNode $choiceNode, array $global)
    {
        $this->formatedData = [
            'name' => uniqid(self::UNIQUEID_PREFIX),
            'weight' => self::DEFAULT_WEIGHT,
        ];

        $this->formatedData = array_merge($this->formatedData, $global);

        foreach ($choiceNode->getChildren() as $element) {
            switch ($element->getId()) {
                case 'token':
                    $data = $element->getValue();
                    switch ($data['token']) {
                        case 'name':
                            $this->formatedData['name'] = (string) $data['value'];
                            break;
                        case 'integer':
                            $this->formatedData['weight'] = (int) $data['value'];
                            break;

                        default:
                            var_dump($data);
                    }
                    break;
                case '#choiceMainValue':
                case '#choiceElement':
                    $this->parseValue($element);
                    break;
                case '#choiceTag':
                    $this->parseTag($element);
                    break;
                case '#tagAdder':
                    $this->parseTagAdder($element);
                    break;
                case '#addChoiceElement':
                    $this->parseAddData($element);
                    break;
            }
        }
    }
    
    public function get()
    {
        return $this->formatedData;
    }


    private function parseValue($node)
    {
        $value = $this->standardDataValue($node, 'text');
        $rawData = $value['value'];
        if (isset($this->formatedData[$value['key']])) {
            if (!is_array($this->formatedData[$value['key']])) {
                $rawData = [$this->formatedData[$value['key']]];
            }
            if (!is_array($rawData)) {
                $rawData = [$rawData];
            }
            $rawData[] = $value['value'];
        }
        $this->formatedData[$value['key']] = $rawData;
    }

    private function parseTag($node)
    {
        $this->formatedData = $this->initiateArray($this->formatedData, 'tags');

        $tag = $node->getChildren();
        $key = $tag[0]->getValue();
        $weight = $tag[1]->getValue();

        $this->formatedData['tags'][$key['value']] = $weight['value'];
    }

    private function parseTagAdder($node)
    {
        $data = $node->getChildren();
        $value = $data[0]->getValue();
        $stepName = '0010-addTag';

        $this->formatedData = $this->initiateArray($this->formatedData, 'mod');
        $this->formatedData['mod'] = $this->initiateArray($this->formatedData['mod'], $stepName);

        $this->formatedData['mod'][$stepName][] = $value['value'];

        unset($data, $value);
    }

    private function parseAddData($node)
    {
        // valeurs par défaut
        $iterationNumber = 1;
        $idValue = 0;
        $stepName = '0020-addNext';

        $data = $node->getChildren();

        if ($data[$idValue]->getId() === '#addChoiceIndicator') {
            $raw = $data[$idValue]->getChildren();
            $token = $raw[0]->getValue();
            switch ($token['token']) {
                case 'atEnd':
                    $stepName = '0001-addAtEnd';
                    break;
                case 'dataMustache':
                    $stepName = '0100-dataMustache';
                    break;
            }
            $idValue++;
        }

        if ($data[$idValue]->getId() === '#addChoiceMultiplicator') {
            $child = $data[$idValue]->getChildren();
            $iterationNumber = (int) $child[0]->getValue()['value'];
            $idValue++;
        }

        $name = $data[$idValue]->getValue();
        // préparation
        $this->formatedData = $this->initiateArray($this->formatedData, 'mod');
        $this->formatedData['mod'] = $this->initiateArray($this->formatedData['mod'], $stepName);

        // Application
        for ($i = 0; $i < $iterationNumber; $i++) {
            $this->formatedData['mod'][$stepName][] = $name['value'];
        }
    }

}