<?php

namespace Gen3se\Engine\Modificator;

/**
 * Ajout de la notion d'experience
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class Experience extends Base
{
    protected $experiences = [];

    private $currentExperienceMultiplicators = [];

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'experience';
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [
            'experience' => [$this, 'addExperience'],
            'experienceTick' => [$this, 'tick'],
        ];
        return $instructions;
    }


    /**
     * Applique les modifications configurés dans le choix pour la notion
     * d'experience
     *
     * @param array $options Paramétrage du choix
     *
     * @return array
     */
    public function apply($options)
    {
        if (!isset($options['experienceRequired'])) {
            return $options;
        }

        foreach ($options['experienceRequired'] as $name => $value) {
            if (!isset($this->experiences[$name])) {
                $options['weight'] = 0;
                continue;
            }
            if ($this->experiences[$name] < $value) {
                $options['weight'] = 0;
                continue;
            }
        }

        return $options;
    }

    /**
     * Renvoi les données d'experience
     *
     * @return array
     */
    public function getDatas()
    {
        return $this->experiences;
    }

    /**
     * Ajout de tags modificateurs pour le scenario en cours
     *
     * @param array|string $option Tags à ajouter au scenario
     *
     * @return self
     */
    public function addExperience($option)
    {
        $this->currentExperienceMultiplicators = $option;

        return null;
    }

    /**
     * Avancement d'une période dans la collecte d'experience
     *
     * @param int $age Nombre de période a avancer
     *
     * @return null
     */
    public function tick($age = 1)
    {
        foreach ($this->currentExperienceMultiplicators as $domaine => $mult) {
            if (!isset($this->experiences[$domaine])) {
                $this->experiences[$domaine] = 0;
            }
            $this->experiences[$domaine] += $mult * $age;
        }

        return null;
    }
}
