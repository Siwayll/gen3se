<?php

namespace Siwayll\Histoire\Modificator;

class Tag extends Base
{
    protected $tags = [];

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'tag';
    }

    public function getInstructions()
    {
        $instructions = [
            'addTag' => [$this, 'addTag'],
            'rmTag' => [$this, 'rmTag'],
        ];
        return $instructions;
    }

    public function apply($options)
    {
        if (!isset($options['tags'])) {
            return $options;
        }

        foreach ($options['tags'] as $tag => $multiplicator) {
            if (!isset($this->tags[$tag])) {
                continue;
            }
            $options['weight'] = ceil($multiplicator * $options['weight']);
        }

        return $options;
    }

    /**
     *
     * @return array
     */
    public function getDatas()
    {
        return $this->tags;
    }

    /**
     * Ajout de tags modificateurs pour le scenario en cours
     *
     * @param array|string $option Tags à ajouter au scenario
     *
     * @return self
     */
    public function addTag($option)
    {
        if (!is_array($option)) {
            $option = [$option];
        }
        foreach ($option as $tag) {
            $key = strtoupper($tag);
            $this->tags[$key] = true;
        }

        return null;
    }

    /**
     * Supprime un tag modificateur pour le scenario en cours
     *
     * @return self
     */
    public function rmTag($option)
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

        return null;
    }

}
