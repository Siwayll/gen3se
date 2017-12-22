<?php

namespace Gen3se\Engine\Modificator;

use \Exception;
use Gen3se\Engine\Error\Level;

/**
 * Ajout de la notion de Tags
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class Tag extends Base
{
    protected $tags = [];

    const SYMBOLES = '&!';

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

        foreach ($options['tags'] as $tag => $mod) {
            if ($this->hasComplexRules($tag) === true) {
                $options['weight'] = $this->applyComplex(
                    $options['weight'],
                    $mod,
                    $tag
                );
                continue;
            }
            if (!isset($this->tags[$tag])) {
                continue;
            }

            if (strpos($mod, '+') === 0) {
                $options['weight'] += $mod;
                continue;
            }

            $options['weight'] = ceil($mod * $options['weight']);
        }

        return $options;
    }

    /**
     * Test si un tag est complexe
     * c.a.d est une somme de tag via &
     *
     *
     * @param string $tag Tag à tester
     *
     * @return boolean
     */
    protected function hasComplexRules($tag)
    {
        if (preg_match('/[' . self::SYMBOLES . ']+/', $tag)) {
            return true;
        }

        return false;
    }

    /**
     * Traite les formules complexe de tags
     *
     * @param int    $weight        Poids de l'option
     * @param float  $multiplicator Multiplicateur du tag
     * @param string $tagStr        Chaine du tag
     *
     * @return int
     */
    protected function applyComplex($weight, $multiplicator, $tagStr)
    {
        $tags = explode('&', $tagStr);
        foreach ($tags as $tag) {
            $seek = true;
            if (strpos($tag, '!') === 0) {
                $seek = false;
                $tag = substr($tag, 1);
            }
            $tag = $this->controlAndFormat($tag);
            if (isset($this->tags[$tag]) !== $seek) {
                return $weight;
            }
        }

        return ceil($multiplicator * $weight);
    }

    /**
     * Renvoi les données des tags
     *
     * @return array
     */
    public function getDatas()
    {
        return array_keys($this->tags);
    }

    /**
     * Contrôle l'absence des caractères interdit et formate le Tag
     *
     * @param string $tag Tag
     *
     * @return string
     * @throws Exception Si il y a un caractère interdit
     */
    private function controlAndFormat($tag)
    {
        if (preg_match('/[' . self::SYMBOLES . ']+/', $tag)) {
            throw new Exception(
                '_' . $tag . '_ mal formaté (' . self::SYMBOLES . ')',
                Level::NOTICE
            );
        }

        return strtoupper($tag);
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
            $key = $this->controlAndFormat($tag);
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
            if (preg_match('/\*$/', $key) === 1) {
                $this->rmTagWithStar($key);
                continue;
            }
            if (isset($this->tags[$key])) {
                unset($this->tags[$key]);
            }
        }

        return null;
    }

    protected function rmTagWithStar($key)
    {
        $key = preg_replace('/\*$/', '', $key);
        foreach ($this->tags as $tagName => $mod) {
            if (strpos($tagName, $key) === false) {
                continue;
            }
            unset($this->tags[$tagName]);
        }
    }
}
