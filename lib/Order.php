<?php

namespace Gen3se\Engine;

/**
 * Gestionnaire des choix à faire
 */
class Order
{
    /**
     * Liste des choix restant à faire
     *
     * @var array
     */
    private $list = [];

    /**
     * Prochain choix à faire
     *
     * @var string
     */
    private $current = null;

    /**
     * Indicateur de présence de Modificator pour l'Engine
     *
     * @return boolean
     */
    public function hasModificators()
    {
        return true;
    }

    /**
     * Liste des instructions à ajouter au moteur
     *
     * @return array
     */
    public function getInstructions()
    {
        return [
            'addAtEnd'   => [$this, 'addAtEnd'],
            'addNext'    => [$this, 'addFurther'],
        ];
    }

    /**
     * Test si il reste des choix dans la liste des choix à faire
     *
     * @return boolean
     */
    public function hasNext()
    {
        return !empty($this->list);
    }

    /**
     * Récupère le prochain choix à faire
     *
     * @return string
     */
    public function getNext()
    {
        $this->current = array_shift($this->list);
        return $this->current;
    }

    /**
     * Ajoute un choix qui sera fait juste après l'actuel
     *
     * @param string $name Name of the order
     *
     * @return self
     */
    public function addFurther($name)
    {
        if (!is_array($name)) {
            $name = [$name];
        }
        array_unshift($this->list, ...$name);
        return $this;
    }

    /**
     * Ajoute un choix à la fin de la liste des choix à faire
     *
     * @param string $name Name of the order
     *
     * @return self
     */
    public function addAtEnd($name)
    {
        if (!is_array($name)) {
            $name = [$name];
        }
        array_push($this->list, ...$name);
        return $this;
    }
}
