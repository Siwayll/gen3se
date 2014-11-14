<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace Siwayll\Histoire;

use \Exception;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
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
     *
     * @var array options chargées
     */
    private $loaded = [];

    private $tags = [];

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
            if (!isset($option[$colName])) {
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
            if ($this->options[$i]['name'] == $name) {
                $this->options[$i] = $newValues;
                break;
            }
        }

        return $this;
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

        $this
            ->setOption($name, $updater->getAll())
            ->resetCaches()
        ;

        return $this;
    }

    /**
     * Ajoute la liste des tags pour éditer le choix
     *
     * @param array $tags liste de tags sous la forme ['nom' => true]
     *
     * @return self
     */
    public function addTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Applique les tags au choix
     *
     * @param array $options Options à modifier
     * @return array
     */
    protected function applyTags($options)
    {
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
     * @return self
     */
    protected function load()
    {
        if (!empty($this->loaded)) {
            return $this;
        }

        foreach ($this->options as $option) {
            $temporyOption = $option;
            if (isset($temporyOption['tags'])) {
                $temporyOption = $this->applyTags($temporyOption);
            }

            $this->loaded[] = $temporyOption;
        }

        return $this;
    }

    public function resetCaches()
    {
        $this->loaded = null;
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

        $this->load();
        $total = 0;
        array_walk($this->loaded, function($value, $key) use (&$total) {
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
        foreach ($this->loaded as $option) {
            $percents[$option['name']] = number_format(($option['weight'] / $total) * 100, 3);
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

        $this->load();

        $start = 0;
        foreach ($this->loaded as $option) {
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
