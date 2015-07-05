<?php

namespace Siwayll\Histoire\Modificator;

use \Siwayll\Histoire\RegisterTrait;

/**
 * Modèle de base d'un modificateur
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
abstract class Base
{
    use RegisterTrait;

    /**
     * Clé identifiant le moteur dans le Registre
     * @var string
     */
    protected $engineKey = '';

    /**
     * Enregistrement dans le registre du modificateur
     */
    public function __construct()
    {
        $prefix = ucfirst(strtolower($this->getName())) . '_';
        $this
            ->setPrefixForRegisterKey($prefix)
            ->generateRegisterKey()
            ->saveToRegister()
        ;
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    abstract public function getInstructions();

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Applique les modifications configurés dans le choix
     *
     * @param array $options Paramétrage du choix
     *
     * @return array
     */
    abstract public function apply($options);

    /**
     * Récupère l'identifiant de l'Engine
     *
     * @param string $engineKey Index du registre contenant l'Engine
     *
     * @return self
     */
    public function linkToEngine($engineKey)
    {
        $this->engineKey = $engineKey;
        return $this;
    }
}
