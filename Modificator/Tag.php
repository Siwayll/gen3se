<?php

namespace Siwayll\Histoire\Modificator;

/**
 * Ajout de la notion de Tags
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
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

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [
            'addTag' => [$this, 'addTag'],
            'rmTag' => [$this, 'rmTag'],
        ];
        return $instructions;
    }

    /**
     * Applique les modifications configurés dans le choix pour la notion
     * de tags
     *
     * @param array $options Paramétrage du choix
     *
     * @return array
     */
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
     * Renvoi les données des tags
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
     * @param array|string $option Tags à supprimer scenario
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
