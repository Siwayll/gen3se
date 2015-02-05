<?php

namespace Siwayll\Histoire\Modificator;

class Temperament extends Base
{
    protected $name = '';

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'temperament';
    }

    public function getInstructions()
    {
        $instructions = [
            'temperamentSetName' => [$this, 'setName'],
            'temperamentSetValue' => [$this, 'setValue'],
        ];
        return $instructions;
    }

    public function apply($options)
    {

        return $options;
    }

    /**
     * Ajout de tags modificateurs pour le scenario en cours
     *
     * @param array|string $option Tags à ajouter au scenario
     *
     * @return self
     */
    public function setName($option)
    {
        $this->name = $option;

        return null;
    }

    /**
     * Supprime un tag modificateur pour le scenario en cours
     *
     * @return self
     */
    public function setValue($option)
    {
        $mark = $this->engine->getModificator('mark');
        $mark->addMark([$this->name => $option]);
        return null;
    }
}
