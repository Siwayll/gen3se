<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace Siwayll\Histoire;

use \Exception;
use Siwayll\Histoire\Register;

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
    protected $name = '';

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
    protected $requiredColumns = ['name', 'weight'];

    /**
     *
     * @var array options chargées
     */
    protected $loaded = [];

    /**
     * Règles à appliquer à toutes les options
     *
     * @var Array
     */
    protected $globalRules = [];


    /**
     * List of Modificators
     *
     * @var array
     */
    protected $modificators = [];

    /**
     * Choix pondéré
     *
     * @param array $config Liste des options pondérés du choix
     *
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

        if (isset($config['globalRules'])) {
            $this->globalRules = $config['globalRules'];
        }

        array_walk($config['options'], [$this, 'controlOption']);

        $this->options = $config['options'];

        unset($config['options'], $config['globalRules'], $config['name']);
        $this->rules = $config;
    }

    /**
     * Renvoie toutes les règles de configurations
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
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
    private function controlOption(&$option, $key)
    {
        if (!isset($option['name']) || $option['name'] == '') {
            throw new Exception(
                'Dans _' . $this->getName() . '_ l\'option __' . $key . '__ '
                    . 'n\'a pas de nom',
                400
            );
        }

        $option = array_merge_recursive($this->globalRules, $option);

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

    public function getOptionNames()
    {
        $names = [];
        foreach ($this->options as $option) {
            $names[] = $option['name'];
        }

        return $names;
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
     * Suppression d'une option d'un choix
     *
     * @param string $name Nom de l'option
     *
     * @return $this
     */
    public function unsetOption($name)
    {
        for ($i = 0; $i < count($this->options); $i++) {
            if ($this->options[$i]['name'] === $name) {
                array_splice($this->options, $i, 1);
                break;
            }
        }

        $this->resetCaches();
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
     * Met à jour une option
     *
     * @param strin $name      nom de l'option
     * @param array $newValues nouvelles valeurs
     *
     * @return self
     * @throws Exception si aucune option ne répond au nom demandé
     */
    protected function setLoadedOption($name, $newValues)
    {
        for ($i = 0; $i < count($this->loaded); $i++) {
            if ($this->loaded[$i]['name'] == $name) {
                $this->loaded[$i] = $newValues;
                break;
            }
        }

        return $this;
    }

    /**
     * Modification d'une option déjà chargée
     *
     * @param string $name      nom de l'option à édtier
     * @param array  $parameter paramètres de modification
     *
     * @return self
     */
    public function updateLoaded($name, $parameter)
    {
        $option = $this->getLoadedOption($name);
        $updater = new ArrayUpdate($option);
        $updater->exec($parameter);

        $this->setLoadedOption($name, $updater->getAll());

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
    public function getLoadedOption($name)
    {
        foreach ($this->loaded as $option) {
            if ($option['name'] == $name) {
                return $option;
            }
        }

        throw new Exception(
            'Dans _' . $this->getName() . '_ l\'option __' . $name . '__ n\'est pas chargée',
            400
        );
    }

    /**
     * Enregistre les modificateurs du Scénario
     *
     * @param object $modificatorKey Objet Modificator
     *
     * @return self
     */
    public function linkToModificator($modificatorKey)
    {
        $this->modificators[] = $modificatorKey;
        return $this;
    }

    /**
     * Charge une option
     *
     * @return self
     */
    public function load()
    {
        if (!empty($this->loaded)) {
            return $this;
        }

        foreach ($this->options as $option) {
            $temporyOption = $option;

            foreach ($this->modificators as $modificatorKey) {
                $modificator = Register::load($modificatorKey);
                $temporyOption = $modificator->apply($temporyOption);
            }

            $this->loaded[] = $temporyOption;
        }

        return $this;
    }

    /**
     * Suppression du cache du choix
     *
     * @return self
     */
    public function resetCaches()
    {
        $this->loaded = null;
        $this->total = null;
        return $this;
    }

    /**
     * Calcule le total des poids des options
     *
     * @param boolean $force si à true, on ignore la mise en cache
     *
     * @return int
     */
    private function getTotal($force = false)
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
            if ($total == 0) {
                $percents[$option['name']] = 0;
                continue;
            }
            if ($option['weight'] == 0) {
                continue;
            }
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
        $this->load();

        $randomizer = new Rand();
        $randValue = $randomizer
            ->setMin(0)
            ->setMax($this->getTotal())
            ->roll()
        ;


        $start = 0;
        foreach ($this->loaded as $option) {
            if ($option['weight'] == 0) {
                continue;
            }
            $start += $option['weight'];
            if ($start >= $randValue) {
                $this->result = $option;
                return $this;
            }
        }
        throw new Exception('Aucun choix possible pour _' . $this->getName() . '_', 400);
    }

    /**
     * Indique si une option est accessible
     *
     * @param $optionName Nom de l'option que l'on veut utiliser
     *
     * @return bool
     */
    public function canIForce($optionName)
    {
        $this->load();

        foreach ($this->loaded as $option) {
            if ($option['name'] != $optionName) {
                continue;
            }

            if ($option['weight'] == 0) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function wantContextData()
    {
        return false;
    }

    public function setContextData($data)
    {

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
