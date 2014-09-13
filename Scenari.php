<?php

namespace Siwayll\Histoire;

use \Exception;

class Scenari
{

    private $data = [];
    private $order = [];
    private $choices = [];
    private $results = [];
    private $current;

    private $instructions = [
        '_nextChoice' => 'setCurrentTo',
        '_addChoice'  => 'addChoice',
        '_addAtEnd'   => 'addChoices',
    ];



    public function __construct(array $data)
    {
        if (empty($data)) {
            throw new Exception('Le scenario doit être un tableau non vide.', 400);
        }

        $this->data = $data;

        if (!isset($data['order'])) {
            throw new Exception('Le scenario doit être un tableau non vide.', 400);
        }

        $this->order = $data['order'];

        foreach ($this->order as $key => $value) {
            $this->order[$key] = $value . uniqid('[%]');
        }

        reset($this->order);

        $this->current = current($this->order);
    }

    /**
     * Change de choix en cours de réalisation
     *
     * @param string $name nom du choix à faire passer "en cours"
     *
     * @return self
     * @throws Exception si aucun choix ne répond au nom demandé
     */
    public function setCurrentTo($name)
    {
        $this->getChoice($name);
        $name .= uniqid('[%]');
        $position = array_search($this->current, $this->order) + 1;
        $base = array_splice($this->order, 0, $position);
        $this->order = array_merge($base, [$name], $this->order);

        $this->restorePosition();

        return $this;
    }

    /**
     * Renvois les données du choix demandé
     *
     * @param string $name nom du choix
     *
     * @return Choice
     * @throws Exception si aucun choix ne répond au nom demandé
     */
    public function getChoice($name)
    {
        if (isset($this->choices[$name])) {
            return $this->choices[$name];
        }

        $choice = null;
        foreach ($this->data['choices'] as $choiceData) {
            $choice = new Choice($choiceData);
            if ($choice->getName() != $name) {
                $choice = null;
                continue;
            }
            $this->choices[$choice->getName()] = $choice;
            break;
        }
        if (empty($choice)) {
            throw new Exception('Aucun choix n\'a le nom _' . $name . '_', 400);
        }

        return $choice;
    }

    public function addChoice($name)
    {
        $name .= uniqid('[%]');
        $this->order[] = $name;

        return $this;
    }

    public function addChoices(array $names)
    {
        $names = array_map(function ($name) {
            $name .= uniqid('[%]');
            return $name;
        }, $names);
        $this->order = array_merge($this->order, $names);
        $this->restorePosition();
        return $this;
    }

    /**
     * Restore la position du current après une reconstruction de l'ordre
     *
     * @return self
     */
    protected function restorePosition()
    {
        reset($this->order);
        while (current($this->order) !== $this->current) {
            next($this->order);
        }

        return $this;
    }

    /**
     * Indique si il reste des choix à faire
     *
     * @return boolean
     */
    public function hasNextChoice()
    {
        if ($this->current === false) {
            return false;
        }

        return true;
    }

    /**
     * Renvois le choix en cours de réalisation
     *
     * @return Choice
     */
    public function getCurrent()
    {
        if ($this->current === false) {
            return false;
        }
        $name = preg_replace('#(\[\%\][a-z0-9]+)$#', '', $this->current);
        return $this->getChoice($name);
    }

    protected function updateScenari($commands)
    {
        foreach ($commands as $command => $option) {
            $funcName = $this->instructions[$command];
            $this->$funcName($option);
        }
    }

    protected function update($command)
    {
        foreach ($command as $name => $update) {
            if ($name == '_scenari') {
                $this->updateScenari($update);
                continue;
            }

            $this->updateChoice($name, $update);
        }

        return $this;
    }

    protected function updateChoice($name, $command)
    {
        $choice = $this->getChoice($name);
        foreach ($command as $name => $parameter) {
            $choice->update($name, $parameter);
        }

        return $this;
    }


    /**
     * Enregistre le resultat du choix en cours
     *
     * @param type $name
     * @return \Siwayll\Histoire\Scenari
     */
    public function setChoiceResult($name)
    {
        $choice = $this->getCurrent();
        $result = $choice->getOption($name);

        $result['_choiceName'] = $choice->getName();
        $this->results[] = $result;

        if (isset($result['onSelect'])) {
            $this->update($result['onSelect']);
        }

        $next = next($this->order);

        $this->current = $next;
        if ($next === false) {
            $this->current = false;
        }
        return $this;
    }


    public function getResults()
    {
        return $this->results;
    }
}
