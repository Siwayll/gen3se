<?php

namespace Siwayll\Histoire;

use \Exception;
use Monolog\Logger;
use Siwayll\Histoire\Choice;
use Siwayll\Histoire\Error\Level;

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
    /**
     * @var Result
     */
    private $result;
    private $modificators = [];

    protected $current;
    protected $currentResultData;

    /**
     * @var Constraint
     */
    private $constraint = null;

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
                    $this->logger->addNotice('Engine Save instruction from ' . $varName, [$code]);
                }
            }
        }

        $this
            ->setPrefixForRegisterKey('Engine_')
            ->generateRegisterKey()
            ->saveToRegister()
        ;
    }

    /**
     * Ajoute des règles de contrainte pour la génération
     *
     * @param Constraint $constraint Gestionnaire de contraintes
     *
     * @return self
     */
    public function addConstraint(Constraint $constraint)
    {
        $this->constraint = $constraint;
        return $this;
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

    /**
     * Donne le logger
     *
     * @return Logger|Monolog\Logger
     */
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
     * @throws Exception Si le Modificator à ajouter est déjà présent
     */
    public function addModificator($modificator)
    {
        if (isset($this->modificators[$modificator->getName()])) {
            $message = 'Modificator ' . $modificator->getName() . ' already present.';
            $this->logger->addWarning($message);
            throw new Exception($message, Level::WARNING);
        }
        $this->modificators[$modificator->getName()] = $modificator;
        $modificators = $modificator->getInstructions();
        foreach ($modificators as $code => $callback) {
            $this->instructions[$code] = $callback;
            $this->logger->addNotice('Engine Save instruction from ' . $modificator->getName(), [$code]);
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
     * @throws Exception Si le Modificator demandé n'est pas présent
     */
    public function getModificator($name)
    {
        if (!isset($this->modificators[$name])) {
            $message = 'Modificator ' . $name . ' not present.';
            $this->logger->addWarning($message);
            throw new Exception($message, Level::ERROR);
        }

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
        ksort($command, SORT_NATURAL);
        foreach ($command as $name => $update) {
            $this->currentResultData = $options;
            $name = preg_replace('/^[0-9]+|-/', '', $name);
            if ($this->isInstruction($name) === true) {
                $this->logger->addNotice('Mod ' . $name, [$update]);
                $additionalDatas = $this->updateScenari($name, $update);
                $options = $this->merge($options, $additionalDatas);
                continue;
            }

            $this->logger->addNotice('Live update ' . $name, [$update]);
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
    private function isInstruction($command)
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

    /**
     * Edition en live des variables d'un choix
     *
     * @param string $name    Nom du choix
     * @param array  $command
     * @param $result
     * @return $this
     */
    protected function updateChoice($name, $command, $result)
    {
        if (strtolower($name) == 'self') {
            $name = $this->current;
            $this->logger->addNotice('Live update choix "self" pour ' . $name, []);
        }
        /** @var Choice $choice */
        $choice = $this->loader->getChoice($name);

        foreach ($command as $name => $parameter) {
            if ($name == 'self') {
                $name = $result['name'];
                $this->logger->addNotice('Live update option "self" pour ' . $name, [$result]);
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
     * Résolution d'un choix
     *
     * @return self
     * @todo Trouver un moyen de charger les storageRule autrement
     */
    public function resolve()
    {
        $choice = $this->getCurrent();
        $this->logger->addDebug('Resolve ' . $choice->getName());

        // Factoriser
        $rules = $choice->getRules();
        if (isset($rules['storageRule'])) {
            $this->result
                ->addStorageRule($choice->getName(), $rules['storageRule'])
            ;
        }

        $choiceInterfaces = class_implements($choice);
        if (isset($choiceInterfaces[__NAMESPACE__ . '\Choice\ContextDataInterface'])) {
            if ($choice->wantContextData() === true) {
                $choice->setContextData(clone $this->result->getStorage());
            }
        }

        if ($this->hasConstraint($choice) === true) {
            return $this->resolveConstraint($choice);
        }

        $this->logger->addDebug('Resolution normale', [$choice->getPercent()]);

        $result = $choice
            ->roll()
            ->getResult()
        ;

        return $this->specifyResult($result);
    }

    /**
     * Contrôle la présence de contrainte pour le choix en cours
     *
     * @param \Siwayll\Histoire\Choice $choice Choix en cours
     *
     * @return bool
     */
    private function hasConstraint(Choice $choice)
    {
        if ($this->constraint === null) {
            return false;
        }

        return $this->constraint->hasRuleFor($choice);
    }

    private function resolveConstraint(Choice $choice)
    {
        $rule = $this->constraint->getRulesFor($choice);
        $result = $rule->selectResult($this, $choice);
        $this->constraint->markAsTreated($choice);

        return $this->specifyResult($result);
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
     * @param array $result
     * @return self
     */
    public function specifyResult($result)
    {
        /** @var Choice $choice */
        $choice = $this->getCurrent();
        $this->logger->addDebug('resultat : ' . $result['name'], $result);
        $result = $this->update($result);
        $this->currentResultData = $result;

        // @todo factoriser les traitements post UPDATE
        if (array_key_exists('consume', $choice->getRules()) === true) {
            $choice->unsetOption($result['name']);
        }

        if (array_key_exists('ignoreForStorage', $choice->getRules()) !== true) {
            $this->result->saveFor($choice->getName(), $result);
        }

        return $this;
    }
}
