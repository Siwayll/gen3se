<?php

namespace Siwayll\Histoire;

use \Exception;

class Choice
{
    /**
     *
     * @var string
     */
    private $name = '';

    protected $result = null;

    private $requiredColumns = ['name', 'text', 'weight'];

    /**
     *
     * @param array $config
     * @throws Exception
     * @todo Utiliser le model d'Helion
     */
    public function __construct(array $config)
    {
        if (empty($config)) {
            throw new Exception('L\'architecture du choix doit être un tableau non vide.', 400);
        }

        // Drop Ici
        if (!isset($config['name']) || empty($config['name'])) {
            throw new Exception('Utilisation d\'un choix sans nom impossible.', 400);
        }
        $this->name = $config['name'];

        // Drop Ici
        if (!isset($config['options']) || empty($config['options'])) {
            throw new Exception('Le choix _' . $this->name . '_ doit avoir des options.', 400);
        }

        array_walk($config['options'], [$this, 'controlOption']);

        $this->options = $config['options'];
    }

    /**
     * Contrôle du format des options
     *
     * @param array $option option à contrôler
     * @param int   $key    place de l'option dans le choix
     *
     * @return self
     * @throws Exception si l'option est mal formaté
     */
    private function controlOption($option, $key)
    {
        if (!isset($option['name']) || empty($option['name'])) {
            throw new Exception(
                'Dans _' . $this->getName() . '_ l\'option __' . $key . '__ '
                    . 'n\'a pas de nom',
                400
            );
        }

        foreach ($this->requiredColumns as $colName) {
            if (!isset($option[$colName]) || empty($option[$colName])) {
                throw new Exception(
                    'Dans _' . $this->getName() . '_ __' . $colName . '__ est '
                        . 'manquant pour _' . $option['name'] . '_',
                    400
                );
            }
        }

        return $this;
    }

    /**
     * Renvois les informations d'une option
     *
     * @param string $name nom de l'option demandée
     *
     * @return array
     * @throws Exception si aucune option ne répond au nom demandé
     */
    public function getOption($name)
    {
        foreach ($this->options as $option) {
            if (!isset($option['name'])) {
                continue;
            }
            if ($option['name'] === $name) {
                return $option;
            }
        }

        throw new Exception('Aucune option n\'a le nom _' . $name . '_', 400);
    }

    /**
     * Modification d'une option
     *
     * @param string $name      nom de l'option à édtier
     * @param array  $parameter paramètres de modification
     *
     * @return self
     */
    public function update($name, $parameter)
    {
        $option = $this->getOption($name);

        return $this;
    }


    /**
     * Calcule le total des poids des options
     *
     * @param boolean $force si à true, on ignore la mise en cache
     *
     * @return int
     */
    protected function getTotal($force = false)
    {
        if ($force === false && !empty($this->total)) {
            return $this->total;
        }

        $total = 0;
        array_walk($this->options, function($value, $key) use (&$total) {
            $total += $value['weight'];
        });
        $this->total = $total;

        return $this->total;
    }

    /**
     *
     * @return self
     * @throws Exception
     */
    public function roll()
    {
        $randomizer = new Rand();
        $randValue = $randomizer
            ->setMin(1)
            ->setMax($this->getTotal())
            ->roll()
        ;

        $start = 0;
        foreach ($this->options as $option) {
            $start += $option['weight'];
            if ($start >= $randValue) {
                $this->result = $option;
                return $this;
            }
        }

        throw new Exception('Aucun choix possible', 400);
    }

    public function getResult()
    {
        return $this->result;
    }


    /**
     * Renvois le nom du choix
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
