<?php

namespace Gen3se\Engine\Modificator;

use \Gen3se\Engine\Register;

/**
 * Ajout de la notion de Tempérament
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class Temperament extends Base
{
    protected $value = 0;

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'temperament';
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [
            'temperamentSetName' => [$this, 'setName'],
            'temperamentSetValue' => [$this, 'setValue'],
        ];
        return $instructions;
    }

    /**
     * Non utilisé
     *
     * @param array $options Paramétrage du choix
     *
     * @return array
     */
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
        $engine = Register::load($this->engineKey);
        $mark = $engine->getModificator('mark');
        $mark->addMark([$option => $this->value]);
        return null;
    }

    /**
     * Enregistre la valeur du temperament
     *
     * @param int $option Valeur du temperament
     *
     * @return self
     */
    public function setValue($option)
    {
        $this->value = (int) $option;

        return null;
    }
}
