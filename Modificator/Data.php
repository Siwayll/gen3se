<?php

namespace Siwayll\Histoire\Modificator;

use \Siwayll\Histoire\Register;

/**
 * Ajout de la possibilité de spécification des données d'une option
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class Data extends Base
{
    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'data';
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [
            'addData' => [$this, 'addData'],
            'dataConcat' => [$this, 'dataConcat'],
        ];
        return $instructions;
    }

    /**
     * Applique les modifications aux options du choix
     *
     * @param array $options Données du choix
     *
     * @return array
     */
    public function apply($options)
    {
        return $options;
    }

    /**
     * Récupère les données des choix demandés pour les ajouter au champ text
     *
     * @param array|string $options Choix à jouer pour récupérer les données
     *
     * @return array
     */
    public function dataConcat($options)
    {
        if (!is_array($options)) {
            $options = [$options];
        }
        $engine = Register::load($this->engineKey);
        $resultData = $engine->getCurrentResultData();
        $text = $resultData['text'];
        $current = $engine->getCurrent()->getName();
        foreach ($options as $choiceName) {
            $result = $engine
                ->setCurrent($choiceName)
                ->getCurrent()
                ->roll()
                ->getResult()
            ;
            $result = $engine->update($result);
            $text .= $result['text'];
        }

        $engine->setCurrent($current);

        $finalResult = [
            'text' => $text,
        ];
        return $finalResult;
    }

    /**
     * Récupère les données des choix demandés pour les ajouter aux données de
     * l'option
     *
     * @param array|string $options Choix à jouer pour récupérer les données
     *
     * @return array
     */
    public function addData($options)
    {
        if (!is_array($options)) {
            $options = [$options];
        }
        $engine = Register::load($this->engineKey);
        $finalResult = $engine->getCurrentResultData();
        foreach ($options as $choiceName) {
            $result = $engine
                ->loadChoice($choiceName)
                ->roll()
                ->getResult()
            ;
            $result = $engine->update($result);

            $finalResult = array_merge($finalResult, $result);
        }

        return $finalResult;
    }
}
