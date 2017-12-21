<?php
/**
 * Created by PhpStorm.
 * User: siwayll
 * Date: 15/08/17
 * Time: 08:52
 */

namespace Siwayll\Gen3se\Ver8e;


use Hoa\Compiler\Llk\TreeNode;

trait StandardTrait
{
    protected function standardDataValue(TreeNode $dataValue, $defaultKey = 'text')
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
     * Crée un nouveau tableau dans $array si la clé $keyName n'existe pas
     *
     * @param array  $array   Tableau dans lequel initialiser le champ
     * @param string $keyName Clé du champ à initialiser
     * @return array
     */
    protected function initiateArray(array $array, string $keyName): array
    {
        if (!isset($array[$keyName])) {
            $array[$keyName] = [];
        }

        return $array;
    }
}