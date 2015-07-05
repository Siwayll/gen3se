<?php

namespace Siwayll\Histoire;

use \Exception;
use Monolog\Logger;

/**
 * Moteur de génération
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class Engine
{
    use RegisterTrait;

    private $loader;
    private $order;
    private $result;

    /**
     * @var Monolog\Logger
     */
    private $logger;

    /**
     * Initialisation du moteur
     *
     * @param object $loader Système de chargement des données
     * @param Order  $order  Gestionnaire de l'ordre de traitement des choix
     * @param Result $result Resultat des choix
     * @param Logger $logger Logger pour debug
     */
    public function __construct($loader, Order $order, Result $result, Logger $logger)
    {
        $this->logger = $logger;
        $varNames = ['loader', 'order', 'result'];
        foreach ($varNames as $varName) {
            $this->{$varName} = ${$varName};
            if ($this->{$varName}->hasModificators() === true) {
                foreach ($this->{$varName}->getInstructions() as $code => $callback) {
                    $this->instructions[$code] = $callback;
                    $this->logger->addDebug('Engine Save instruction from ' . $varName, [$code]);
                }
            }
        }

        $this
            ->generateRegisterKey()
            ->saveToRegister()
        ;
    }

    /**
     * Renvoi le gestionnaire d'ordre des choix
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Renvoi le gestionnaire des résultats
     *
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Renvoi le système de chargement des données
     *
     * @return object
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Défini le choix en cours de réalisation
     *
     * @param string $name Nom du choix
     *
     * @return self
     */
    public function setCurrent($name)
    {
        $this->current = $name;
        return $this;
    }

    /**
     * Ajout d'un Modificateur
     *
     * @param object $modificator Modificateur à ajouter
     *
     * @return self
     */
    public function addModificator($modificator)
    {
        $this->modificators[$modificator->getName()] = $modificator;
        $modificators = $modificator->getInstructions();
        foreach ($modificators as $code => $callback) {
            $this->instructions[$code] = $callback;
            $this->logger->addDebug('Engine Save instruction', [$code]);
        }

        $modificator->linkToEngine($this->getRegisterKey());
        return $this;
    }

    /**
     * Renvoi le Modificateur demandé
     *
     * @param string $name Nom du Modificateur
     *
     * @return object
     */
    public function getModificator($name)
    {
        return $this->modificators[$name];
    }

    /**
     * Fusion des données
     *
     * @param array       $options     Données de base
     * @param array|mixed $anotherData Données à ajouter
     *
     * @return array
     */
    protected function merge($options, $anotherData)
    {
        if (empty($anotherData) || !is_array($anotherData)) {
            return $options;
        }
        return array_merge($options, $anotherData);
    }

    /**
     * Renvoi les resultats du choix qui vient d'être résolu
     *
     * @return array
     */
    public function getCurrentResultData()
    {
        return $this->currentResultData;
    }


    public function update($options)
    {
        if (!isset($options['mod'])) {
            return $options;
        }



        $command = $options['mod'];
        foreach ($command as $name => $update) {
            if ($this->isInstruction($name) === true) {
                $additionalDatas = $this->updateScenari($name, $update);
                $options = $this->merge($options, $additionalDatas);
                continue;
            }

            $this->updateChoice($name, $update, $options);
        }

        return $options;
    }

    /**
     * Test if a command is a saved instruction
     *
     * @param string $command Command string
     *
     * @return boolean
     */
    protected function isInstruction($command)
    {
        if (isset($this->instructions[$command])) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param array $commands
     */
    protected function updateScenari($code, $option)
    {
        return call_user_func($this->instructions[$code], $option);
    }

    protected function updateChoice($name, $command, $result)
    {
        if (strtolower($name) == 'self') {
            $name = $this->current;
        }
        $choice = $this->loader->getChoice($name);

        foreach ($command as $name => $parameter) {
            if ($name == 'self') {
                $name = $result['name'];
            }
            $choice->update($name, $parameter);
        }

        return $this;
    }

    public function init()
    {
        $this->current = $this->order->getNext();
        return $this;
    }

    /**
     *
     * @param string $choiceName Name of the Choice
     *
     * @return self
     */
    public function resolve($choiceName = null)
    {
        if (empty($choiceName)) {
            $choice = $this->getCurrent();
        } else {
            $choice = $this->loadChoice($choiceName);
        }

        // Factoriser
        $rules = $choice->getRules();
        if (isset($rules['storageRule'])) {
            $this->result
                ->addStorageRule($choice->getName(), $rules['storageRule'])
            ;
        }

        $result = $choice
            ->roll()
            ->getResult()
        ;
        return $this->specifyResult($result['name']);
    }

    /**
     * Resolve all Choice present in Order
     *
     * @return self
     */
    public function resolveAll()
    {
        do {
            $this
                ->next()
                ->resolve()
            ;
        } while ($this->order->hasNext());

        return $this;
    }


    public function getCurrent()
    {
        if ($this->current === false) {
            return null;
        }

        return $this->loadChoice($this->current);
    }

    /**
     * Charge et parametre un choix
     *
     * @var string $choiceName Nom du choix
     *
     * @return Choice
     */
    public function loadChoice($choiceName)
    {
        $choice = $this->loader->getChoice($choiceName);
        /* @var $mod Modificator\Base */
        foreach ($this->modificators as $mod) {
            $choice->linkToModificator($mod->getRegisterKey());
        }
        return $choice;
    }

    public function next()
    {
        $next = $this->order->getNext();
        $this->current = $next;
        if ($next === false) {
            $this->current = false;
        }
        return $this;
    }

    /**
     * Enregistre le resultat du choix en cours
     *
     * @param type $name
     * @return self
     */
    public function specifyResult($name)
    {
        $choice = $this->getCurrent();
        $result = $choice->getOption($name, true);
        $choice->resetCaches();

        $this->currentResultData = $result;

        // post traitement
        if (isset($result['mod'])) {
            $result = $this->update($result);
        }

        $this->result->saveFor($choice->getName(), $result);
        return $this;
    }
}
