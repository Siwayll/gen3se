<?php

namespace Siwayll\Gen3se\Loader;

use \Exception;
use Hoa\Compiler\Llk\TreeNode;
use Siwayll\Gen3se\Choice;
use Siwayll\Gen3se\ChoiceData;
use Siwayll\Gen3se\LoaderInterface;
use Siwayll\Gen3se\RegisterTrait;

class FromCompiler implements LoaderInterface
{
    use RegisterTrait;
    private $order = [];
    private $choices = [];
    private $loaded = [];

    /**
     * Information sur l'ajout de Modificateurs
     *
     * @return bool
     */
    public function hasModificators(): bool
    {
        return false;
    }

    public function getInstructions(): array
    {
        return [];
    }

    public function __construct()
    {

    }

    public function addChoice(TreeNode $choiceData)
    {
        $rawData = [
            'name' => null,
            'options' => []
        ];

        $global = [];

        foreach ($choiceData->getChildren() as $element) {
            switch ($element->getId()) {
                case 'token':
                    $data = $element->getValue();
                    switch ($data['token']) {
                        case 'name':
                            $rawData['name'] = $data['value'];
                            break;
                        default:
                            var_dump($data);
                    }
                    break;
                case '#choiceStorage' :
                    $storageRule = [];
                    foreach ($element->getChildren() as $subElement) {
                        if ($subElement->getId() != 'token') {
                            continue;
                        }

                        $storageRule[] = $subElement->getValue()['value'];
                    }
                    $rawData['storageRule'] = $storageRule;
                    unset($storageRule);
                    break;
                case '#choiceOption' :
                    $rawData['options'][] = $this->loadOption($element, $global);
                    break;
                case '#choiceElement':
                    $value = $this->loadChoiceDataValue($element, 'global');
                    $global[$value['key']] = $value['value'];
                    unset($value);
                    break;
                default:
                    var_dump($element->getId());
            }
        }
        $this->choices[$rawData['name']] = $rawData;
    }

    private function loadChoiceDataValue($dataValue, $defaultKey = 'text')
    {
        $return = [
            'key' => $defaultKey,
            'value' => null,
        ];
        $elements = $dataValue->getChildren();
        $target = 0;
        if (count($elements) == 2) {
            $return['key'] = $elements[0]->getValue()['value'];
            $target = 1;
        }

        if (isset($elements[$target])) {
            $data = $elements[$target]->getValue();
            $return['value'] = $data['value'];
            if ($data['token'] ==  'null') {
                $return['value'] = null;
            }

        }

        return $return;
    }

    private function loadOption(TreeNode $choiceOption, array $global)
    {

        $archi = [
            'name' => uniqid('auto_'),
            'weight' => 100,
        ];

        $archi = array_merge($archi, $global);

        foreach ($choiceOption->getChildren() as $element) {
            switch ($element->getId()) {
                case 'token':
                    $data = $element->getValue();
                    switch ($data['token']) {
                        case 'name':
                            $archi['name'] = (string) $data['value'];
                            break;
                        case 'integer':
                            $archi['weight'] = (int) $data['value'];
                            break;

                        default:
                            var_dump($data);
                    }
                    break;
                case '#choiceMainValue':
                    $value = $this->loadChoiceDataValue($element, 'text');
                    $archi[$value['key']] = $value['value'];
                    unset($value);
                    break;
                case '#choiceTag':
                    if (!isset($archi['tags'])) {
                        $archi['tags'] = [];
                    }

                    $tag = $element->getChildren();
                    $key = $tag[0]->getValue();
                    $weight = $tag[1]->getValue();

                    $archi['tags'][$key['value']] = $weight['value'];
                    break;

                default:
                    var_dump($element->getId());
            }
        }

        return $archi;
    }

    /**
     * Renvois l'ordre de traitement des choix
     *
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Charge un choix
     *
     * @param string $name Nom du choix
     *
     * @return Choice
     * @throws Exception si aucun choix n'éxiste avec ce nom
     */
    public function getChoice(string $name): Choice
    {
        if (isset($this->loaded[$name])) {
            return $this->loaded[$name];
        }

        if (isset($this->choices[$name])) {
            $choiceData = new ChoiceData($this->choices[$name]);
            $this->loaded[$name] = new Choice($choiceData);
            return $this->loaded[$name];
        }

        throw new Exception('Aucun choix n\'a le nom _' . $name . '_', 400);
    }
}
