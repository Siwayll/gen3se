<?php

namespace Siwayll\Histoire;

use \Exception;

class ArrayUpdate
{
    private $data = [];

    /**
     * correspondance instruction / méthode
     *
     * @var array
     */
    private $instructions = [
        '_inc' => 'increment',
        '_dec' => 'decrement',
        '_set' => 'set',
        '_add' => 'add',
        '_app' => 'append',
        '_unset' => 'delete',
        '_rename' => 'rename',
    ];

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
     * Test si une instruction est valide
     *
     * @param string $name Nom de l'instruction
     *
     * @return self
     * @throws Exception si le nom n'est pas valide
     */
    protected function isInstruction($name)
    {
        if (!isset($this->instructions[$name])) {
            throw new Exception('__' . $name . '__ n\'est pas une instruction valide', 600);
        }
        return $this;
    }

    /**
     * Execution multicolonne de l'instruction
     *
     * @param string $instructionName nom de l'instruction
     * @param array  $targets         tableau formalisé de cible
     *
     * @return self
     */
    protected function launchInstruction($instructionName, $targets)
    {
        $functionName = $this->instructions[$instructionName];
        if (!is_array($targets)) {
            $targets = [
                $targets => null
            ];
        }
        foreach ($targets as $fieldName => $value) {
            $this->$functionName($fieldName, $value);
        }
        return $this;
    }

    /**
     *
     *
     * @param array $command Commande d'update du tableau
     *
     * @return self
     */
    public function exec(array $command)
    {
        foreach ($command as $instruction => $target) {
            $this
                ->isInstruction($instruction)
                ->launchInstruction($instruction, $target)
            ;
        }

        return $this;
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
        if (!$this->isPresent($name)) {
            throw new Exception('__' . $name . '__ n\'existe pas', 400);
        }

        return $this->data[$name];
    }

    /**
     * Renvois toutes les données du tableau
     *
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Test l'existence d'un champ
     *
     * @param string $name nom du champ
     *
     * @return boolean
     */
    public function isPresent($name)
    {
        return isset($this->data[$name]);
    }


    /**
     * Remplace une variable au champ demandé
     *
     * @param string $name  nom du champ
     * @param mixed  $value valeur à mettre à la place
     *
     * @return self
     */
    protected function set($name, $value)
    {
        if (!$this->isPresent($name)) {
            throw new Exception('__' . $name . '__ n\'existe pas', 400);
        }

        if ($value === null) {
            $value = '';
        }

        $this->data[$name] = $value;

        return $this;
    }

    /**
     * Ajoute un champ au tableau
     *
     * @param string $name  nom du champ
     * @param mixed  $value valeur du champ
     *
     * @return self
     */
    protected function add($name, $value)
    {
        if ($this->isPresent($name)) {
            if (!is_array($this->data[$name])) {
                throw new Exception('__' . $name . '__ existe déjà', 400);
            }
            $this->set($name, array_merge($this->data[$name], $value));
            return $this;
        }

        $this->data[$name] = $value;

        return $this;
    }


    /**
     * Supprime un champ
     *
     * @param string $name nom du champ à supprimer
     *
     * @return self
     */
    protected function delete($name)
    {
        if (!$this->isPresent($name)) {
            throw new Exception('__' . $name . '__ n\'existe pas', 400);
        }
        unset($this->data[$name]);
        return $this;
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
    protected function increment($name, $amount = null)
    {
        if ($amount === null) {
            $amount = 1;
        }
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
    protected function decrement($name, $amount = null)
    {
        if ($amount === null) {
            $amount = 1;
        }
        $value = $this->get($name);
        if ($value !== (int) $value) {
            throw new Exception('__' . $name . '__ n\'est pas de type numérique', 400);
        }
        $value -= $amount;
        $this->set($name, $value);

        return $this;
    }

    /**
     * Change le nom d'un champ
     *
     * Le nouveau nom ne peut pas être un champ déjà existant
     *
     * @param string $name    nom du champ
     * @param string $newName nouveau nom du champ
     *
     * @return self
     * @throws Exception si le nouveau nom n'est pas une chaine
     */
    protected function rename($name, $newName)
    {
        if (!is_string($newName)) {
            throw new Exception(
                'le nouveau nom de __' . $name . '__ n\'est pas une chaine',
                400
            );
        }
        $value = $this->get($name);
        $this
            ->delete($name)
            ->add($newName, $value)
        ;
        return $this;
    }

    /**
     * Ajoute une chaine à la fin du champ
     *
     * @param string $name   nom du champ
     * @param string $string chaine à ajouter à la fin
     *
     * @return self
     */
    protected function append($name, $string)
    {
        $value = (string) $this->get($name);
        $value .= $string;
        $this->set($name, $value);

        return $this;
    }
}
