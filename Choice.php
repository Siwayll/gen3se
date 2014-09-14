<?php

namespace Siwayll\Histoire;

use \Exception;

class Choice
{
    /**
     * Nom identifiant le choix
     *
     * @var string
     */
    private $name = '';

    /**
     * Résultat de la séléction aléatoire parmis les options du choix
     *
     * @var array
     */
    protected $result = null;

    /**
     * Nom des colonnes obligatoires pour caractériser une option du choix
     *
     * @var string[]
     */
    private $requiredColumns = ['name', 'text', 'weight'];

    /**
     * Choix pondéré
     *
     * @param array $config Liste des options pondérés du choix
     *
     * @return void
     * @throws Exception si les options sont mal formatés
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
        if (!isset($option['name']) || $option['name'] == '') {
            throw new Exception(
                'Dans _' . $this->getName() . '_ l\'option __' . $key . '__ '
                    . 'n\'a pas de nom',
                400
            );
        }

        foreach ($this->requiredColumns as $colName) {
            if (!isset($option[$colName]) || $option[$colName] == '') {
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
            if ($option['name'] == $name) {
                return $option;
            }
        }

        throw new Exception(
            'Dans _' . $this->getName() . '_ l\'option __' . $name . '__ n\'existe pas',
            400
        );
    }

    /**
     * Met à jour une option
     *
     * @param strin $name      nom de l'option
     * @param array $newValues nouvelles valeurs
     *
     * @return self
     * @throws Exception si aucune option ne répond au nom demandé
     */
    protected function setOption($name, $newValues)
    {
        for ($i = 0; $i < count($this->options); $i++) {
            if (!isset($this->options[$i]['name'])) {
                continue;
            }
            if ($this->options[$i]['name'] == $name) {
                $this->options[$i] = $newValues;
                return $this;
            }
        }

        throw new Exception(
            'Dans _' . $this->getName() . '_ l\'option __' . $name . '__ n\'existe pas',
            400
        );
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
        $updater = new ArrayUpdate($option);
        $updater->exec($parameter);

        $this->setOption($name, $updater->getAll());

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
     * Renvoie la répartition des choix en pourcentage
     *
     * @return array
     */
    public function getPercent()
    {
        $total = $this->getTotal(true);
        $percents = [];
        foreach ($this->options as $option) {
            $percents[$option['name']] = ($option['weight'] / $total) * 100;
        }

        return $percents;
    }

    /**
     * Choisi une option aléatoirement
     *
     * @return self
     * @throws Exception si aucun choix n'est possible
     * @uses Rand
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

    /**
     * Renvois l'option choisi aléatoirement
     *
     * @return array
     */
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
