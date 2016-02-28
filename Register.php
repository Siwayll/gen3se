<?php

namespace Siwayll\Histoire;

use \Exception;

/**
 * Stockage des éléments de génération
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class Register
{
    private static $registre = [];
    private static $typeDefault = [];

    /**
     * Enregistre l'élément dans le registre
     *
     * @param string $name   Nom sous lequel enregistrer l'élément
     * @param mixed  $object Element à enregistrer
     *
     * @return void
     */
    public static function save($name, $object)
    {
        self::$registre[$name] = $object;
    }

    /**
     * Renvoi un élément du registre
     *
     * @param string $name Nom de l'élément à renvoyer
     *
     * @return mixed
     * @throws
     */
    public static function load($name)
    {
        if (!isset(self::$registre[$name])) {
            throw new Exception('_' . $name . '_ n\'est pas enregistré dans le registre');
        }

        return self::$registre[$name];
    }

    public static function loadByType($typeName)
    {
        if (!isset(self::$typeDefault[$typeName])) {
            throw new Exception('_' . $typeName . '_ n\'a pas de valeur par défaut');
        }

        return self::$registre[self::$typeDefault[$typeName]];
    }

    public static function setTypeDefault($name, $type)
    {
        self::$typeDefault[$type] = $name;
    }


    /**
     * Supprime l'élément du registre
     *
     * @param string $name Nom de l'élément à supprimer
     *
     * @return void
     */
    public static function del($name)
    {
        unset(self::$registre[$name]);
    }
}
