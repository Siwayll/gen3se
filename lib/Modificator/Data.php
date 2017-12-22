<?php

namespace Gen3se\Engine\Modificator;

use Gen3se\Engine\Engine;
use \Gen3se\Engine\Register;

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
            'dataMustache' => [$this, 'addDataMustache'],
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

        $field = 'text';
        if (isset($options['field'])) {
            $field = $options['field'];
        }

        $separator = '';
        if (isset($options['separator'])) {
            $separator = $options['separator'];
        }

        if (isset($options['choices'])) {
            $options = $options['choices'];
        }
        // chargement de la base déjà choisie
        /** @var Engine $engine */
        $engine = Register::load($this->engineKey);
        $logger = $engine->getLogger();
        $resultData = $engine->getCurrentResultData();
        $text = '';
        if (isset($resultData[$field])) {
            $text = $resultData[$field];
        }

        $current = $engine->getCurrent()->getName();
        foreach ($options as $choiceName) {
            $engine->setCurrent($choiceName)->resolve();
            $result = $engine->getCurrentResultData();
            $text .= $separator . $result[$field];
            $logger->addDebug('Mod concat [' . $result[$field] . ']', ['state' => $text]);
        }

        $engine->setCurrent($current);

        $finalResult = [
            $field => trim($text, $separator),
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

        /** @var \Monolog\Logger $logger */
        $logger = $engine->getlogger();
        $logger->addNotice('addData to ' . $finalResult['name'], [$finalResult]);

        $current = $engine->getCurrent()->getName();
        foreach ($options as $choiceName) {
            $result = $engine
                ->loadChoice($choiceName)
                ->roll()
                ->getResult()
            ;
            $logger->addDebug('addData add ' . $result['name'], [$result]);
            $result = $engine->update($result);

            $finalResult = array_merge($result, $finalResult);
        }

        $logger->addDebug('addData restoration current ' . $current, [$finalResult]);
        $engine->setCurrent($current);

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
    public function addDataMustache($options)
    {
        if (!is_array($options)) {
            $options = [$options];
        }
        $engine = Register::load($this->engineKey);
        $finalResult = $engine->getCurrentResultData();

        /** @var \Monolog\Logger $logger */
        $logger = $engine->getlogger();
        $logger->addNotice('addData to ' . $finalResult['name'], [$finalResult]);

        $mustacheEngine = new \Mustache_Engine();
        $mustacheEngine->addHelper('case', [
            'lower' => function($value) { return strtolower((string) $value); },
            'upper' => function($value) { return strtoupper((string) $value); },
        ]);

        $current = $engine->getCurrent()->getName();
        $data = [];
        foreach ($options as $choiceName) {
            $result = $engine
                ->loadChoice($choiceName)
                ->roll()
                ->getResult()
            ;
            $logger->addDebug('addData add ' . $result['name'], [$result]);
            $result = $engine->update($result);

            $data = array_merge($data, $result);

        }
        foreach ($finalResult as $key => $value) {
            if (!is_string($value)) {
                continue;
            }
            $finalResult[$key] = $mustacheEngine->render($value, $data);
        }

        $logger->addDebug('addData restoration current ' . $current, [$finalResult]);
        $engine->setCurrent($current);

        return $finalResult;
    }
}
