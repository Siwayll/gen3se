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

    private $loader;
    private $order;
    private $result;

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
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setCurrent($name)
    {
        $this->current = $name;
        return $this;
    }

    /**
     * add a modificator
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

        $modificator->linkToEngine($this);
        return $this;
    }

    /**
     *
     * @param string $name Modificator name
     * @return type
     */
    public function getModificator($name)
    {
        return $this->modificators[$name];
    }

    /**
     * undocumented function
     *
     * @return void
     */
    protected function merge($options, $anotherData)
    {
        if (empty($anotherData) || !is_array($anotherData)) {
            return $options;
        }
        return array_merge($options, $anotherData);
    }

    public function getCurrentResultData()
    {
        return $this->currentResultData;
    }


    public function update($options)
    {
        $this->currentResultData = $options;
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
        return call_user_func($this->instructions[$code],  $option);
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
        } while($this->order->hasNext());

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
        foreach ($this->modificators as $mod) {
            $choice->linkToModificator($mod);
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
     * @return \Siwayll\Histoire\Scenari
     */
    public function specifyResult($name)
    {
        $choice = $this->getCurrent();
        $result = $choice->getOption($name, true);
        $choice->resetCaches();

//        $result['_choiceName'] = $choice->getName();

        // post traitement
        if (isset($result['mod'])) {
            $result = $this->update($result);
        }

        $this->result->saveFor($choice->getName(), $result);
        return $this;
    }
}
