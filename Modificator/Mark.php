<?php

namespace Siwayll\Gen3se\Modificator;

/**
 * Gestionnaire des Mark
 *
 */
class Mark extends Base
{
    protected $marks = [];

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'mark';
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [
            'addMark' => [$this, 'addMark'],
        ];
        return $instructions;
    }

    /**
     * Applique les modifications configurées dans la section Mark du choix
     *
     * @param array $options Paramétrage du choix
     *
     * @return array
     */
    public function apply($options)
    {
        if (!isset($options['marks'])) {
            return $options;
        }

        foreach ($options['marks'] as $mark) {
            $mark = strtoupper($mark);
            switch ($this->getSymbole($mark)) {
                case '!':
                    // interdit
                    if (isset($this->marks[$mark])) {
                        $options['weight'] = 0;
                    }
                    break;
                case '#':
                    // obligatoire
                    if (!isset($this->marks[$mark])) {
                        $options['weight'] = 0;
                    } else {
                        $options['weight'] += 2 * (int) $this->marks[$mark];
                    }
                    break;
                case '-':
                    // négatif
                    if (isset($this->marks[$mark])) {
                        $options['weight'] -= (int) $this->marks[$mark];
                        if ($options['weight'] < 0) {
                            $options['weight'] = 0;
                        }
                    }
                    break;

                default:
                    if (isset($this->marks[$mark])) {
                        $options['weight'] += (int) $this->marks[$mark];
                    }
                    break;
            }
        }

        return $options;
    }

    /**
     * Récupère le symbole (si présent) et le retire de la chaine
     *
     * @param string $mark Chaine identifiant la Mark
     *
     * @return string
     */
    protected function getSymbole(&$mark)
    {
        $pattern = '/^([#\+\-\!]{1})/';
        preg_match($pattern, $mark, $match);
        if (isset($match[1])) {
            $mark = preg_replace($pattern, '', $mark);
            return $match[1];
        }

        return '+';
    }

    /**
     * Renvoi la liste des marks enregsitrées
     *
     * @return array
     */
    public function getDatas()
    {
        return $this->marks;
    }

    /**
     * Ajoute une ou plusieurs Mark à l'Engine
     *
     * @param array $option Tags à ajouter au scenario
     *
     * @return self
     */
    public function addMark($option)
    {
        foreach ($option as $name => $weight) {
            $name = strtoupper($name);
            if (isset($this->marks[$name])) {
                $this->marks[$name] += $weight;
                continue;
            }
            $this->marks[$name] = $weight;
        }

        return null;
    }
}
