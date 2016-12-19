<?php

namespace Siwayll\Gen3se\Modificator;


/**
 * Aide au paramétrage des enchaînements de génération pour les noms
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class NameSound extends Base
{

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'namesound';
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [];
        return $instructions;
    }

    /**
     * Ajoute les mods spécifique au module SOUND
     *
     * @param array $options Paramétrage du choix
     *
     * @return array
     */
    public function apply($options)
    {
        if (array_key_exists('sound', $options) !== true) {
            return $options;
        }

        $particle = $options['text'];
        if (empty($particle)) {
            return $options;
        }
        $end = substr($particle, -1);

        $cat = 'CONSONNE';
        if (strpos('aeiouy', $end) !== false) {
            $cat = 'VOYELLE';
        }

        if (!isset($options['mod'])) {
            $options['mod'] = [];
        }
        $options['mod']['0000-rmTag'] = ['SOUND_*'];
        $options['mod']['0009-addTag'] = ['SOUND_' . strtoupper($end), 'SOUND_' . $cat];

        return $options;
    }
}
