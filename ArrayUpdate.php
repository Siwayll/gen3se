<?php

namespace Siwayll\Histoire;

use \Exception;

class ArrayUpdate
{
    private $data = [];

    /**
     * Edition d'un tableau
     *
     * @param array $array tableau à édtier
     *
     * @return void
     */
    public function __construct(array $array)
    {
        if (empty($array)) {
            throw new Exception('Un tableau non vide est nécessaire', 400);
        }

        $this->data = $array;
    }

    /**
     * Renvois le champ du tableau
     *
     * @param string $name nom du champ à renvoyer
     *
     * @return mixed
     * @throws Exception si le champ n'existe pas
     */
    public function get($name)
    {
        if (!isset($this->data[$name])) {
            throw new Exception('__' . $name . '__ n\'existe pas', 400);
        }

        return $this->data[$name];
    }

    protected function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Incrémente la valeur du champ demandé
     *
     * @param string $name   nom du champ
     * @param int    $amount valeur de l'incrément
     *
     * @return self
     * @throws Exception si la valeur n'est pas un entier
     */
    public function increment($name, $amount = 1)
    {
        $value = $this->get($name);
        if ($value !== (int) $value) {
            throw new Exception('__' . $name . '__ n\'est pas de type numérique', 400);
        }

        $value += $amount;
        $this->set($name, $value);

        return $this;
    }

    /**
     * Décrémente la valeur du champ demandé
     *
     * @param string $name   nom du champ
     * @param int    $amount valeur de l'incrément
     *
     * @return self
     * @throws Exception si la valeur n'est pas un entier
     */
    public function decrement($name, $amount = 1)
    {
        $value = $this->get($name);
        if ($value !== (int) $value) {
            throw new Exception('__' . $name . '__ n\'est pas de type numérique', 400);
        }
        $value -= $amount;
        $this->set($name, $value);

        return $this;
    }
}
