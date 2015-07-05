<?php

namespace Siwayll\Histoire\Modificator;

use \Siwayll\Histoire\Register;

/**
 * Ajout de la notion d'experience
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class Beauty extends Base
{

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'beauty';
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [
            'beautyAdd' => [$this, 'add'],
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
     * Evolution du score de beautée
     *
     * @param int $option Modificateur du score de beauté
     *
     * @return self
     */
    public function add($option)
    {
        $value = (int) $option;
        $engine = Register::load($this->engineKey);
        $npc = $engine->getResult()->getStorage();

        if (!isset($npc->description->charme->history)) {
            $npc->set([], 'description', 'charme', 'history');
        }

        $npc->description->charme->history[] = 'var ' . $option;

        if (isset($npc->description->charme->score)) {
            $value += $npc->description->charme->score;
        }

        $npc->set($value, 'description', 'charme', 'score');

        return null;
    }
}
