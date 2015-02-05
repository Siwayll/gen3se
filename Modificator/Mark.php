<?php

namespace Siwayll\Histoire\Modificator;

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
            '_addMark' => [$this, 'addMark'],
        ];
        return $instructions;
    }

    public function apply($options)
    {
        if (!isset($options['marks'])) {
            return $options;
        }

        foreach ($options['marks'] as $mark) {
            $mark = strtoupper($mark);
            switch ($this->getSymbole($mark)) {
                case '!': // interdit
                    if (isset($this->marks[$mark])) {
                        $options['weight'] = 0;
                    }
                    break;
                case '#': // obligatoire
                    if (!isset($this->marks[$mark])) {
                        $options['weight'] = 0;
                    } else {
                        $options['weight'] += 2 * (int) $this->marks[$mark];
                    }
                    break;
                case '-': // négatif
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

    protected function getSymbole(&$mark)
    {
        $pattern = '/^([#\+\-\!]?)/';
        preg_match($pattern, $mark, $match);
        if (isset($match[1])) {
            $mark = preg_replace($pattern, '', $mark);
            return $match[1];
        }

        return '+';
    }

    /**
     *
     * @return array
     */
    public function getDatas()
    {
        return $this->marks;
    }

    /**
     *
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
