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

    /**
     * Liste des Tags (étiquettes) caractérisant l'arbre de choix
     *
     * @var array
     */
    private $tags = [];

    private $instructions = [
        '_nextChoice' => 'setCurrentTo',
        '_addAtEnd'   => 'addChoices',
        '_addNext'    => 'addChoicesNext',
        '_addTag'     => 'addTag',
        '_rmTag'      => 'rmTag',
    ];



    public function __construct($data)
    {
        if (empty($data)) {
            throw new Exception('Le scenario doit être un tableau non vide.', 400);
        }

        $this->data = $data;
        $this->order = $data->getOrder();

        foreach ($this->order as $key => $value) {
            $this->order[$key] = $value . uniqid('[%]');
        }
        reset($this->order);

        $this->current = current($this->order);
    }

    /**
     * Renvoie le nombre total de choix à faire pour ce scenario
     *
     * Attention, ce chiffre peut évoluer au cours de la réalisation du scenario
     *
     * @return self
     */
    public function getNumberOfChoices()
    {
        return count($this->order);
    }

    /**
     * Renvois la liste des choix à faire lisible.
     * Les identifiants uniques sont donc supprimés
     *
     * @return array
     */
    public function getOrder()
    {
        $readableOrder = [];
        foreach ($this->order as $order) {
            $readableOrder[] = substr($order, 0, strpos($order, '[%]'));
        }
        return $readableOrder;
    }

    /**
     * Change de choix en cours de réalisation
     *
     * @param string $name Nom du choix à faire passer "en cours"
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
     * @param string $name Nom du choix
     *
     * @return Choice
     * @throws Exception si aucun choix ne répond au nom demandé
     */
    public function getChoice($name)
    {
        if (isset($this->choices[$name])) {
            return $this->choices[$name];
        }

        $this->choices[$name] = $this->data->getChoice($name);
        $this->choices[$name]->addTags($this->tags);
        return $this->choices[$name];
    }

    public function addChoicesNext($names)
    {
        if (!is_array($names)) {
            $names = [$names];
        }

        $names = array_map(function ($name) {
            $name .= uniqid('[%]');
            return $name;
        }, $names);

        $order = $this->order;
        $orderEnd = array_splice($order, array_search($this->current, $order) + 1);

        $this->order = array_merge($order, $names, $orderEnd);
        $this->restorePosition();
        return $this;
    }

    public function addChoices($names)
    {
        if (!is_array($names)) {
            $names = [$names];
        }
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

    /**
     * Ajout de tags modificateurs pour le scenario en cours
     *
     * @param array|string $option Tags à ajouter au scenario
     *
     * @return self
     */
    protected function addTag($option)
    {
        if (!is_array($option)) {
            $option = [$option];
        }
        foreach ($option as $tag) {
            $key = strtoupper($tag);
            $this->tags[$key] = true;
        }

        return $this;
    }

    /**
     * Supprime un tag modificateur pour le scenario en cours
     *
     * @return self
     */
    protected function rmTag($option)
    {
        if (!is_array($option)) {
            $option = [$option];
        }
        foreach ($option as $tag) {
            $key = strtoupper($tag);
            if (isset($this->tags[$key])) {
                unset($this->tags[$key]);
            }
        }

        return $this;
    }


    /**
     *
     * @param array $commands
     */
    protected function updateScenari($commands)
    {
        foreach ($commands as $command => $option) {
            $funcName = $this->instructions[$command];
            $this->$funcName($option);
        }

        return $this;
    }

    public function update($command)
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
        $choice->resetCaches();

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
