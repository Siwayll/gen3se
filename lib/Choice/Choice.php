<?php

namespace Gen3se\Engine\Choice;

use \Exception;
use Gen3se\Engine\Exception\RuleDoesNotExist;
use Gen3se\Engine\Exception\RuleHasNotOption;
use Gen3se\Engine\Register;
use Siwayll\Gen3se\ChoiceData;

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
     * Liste des solutions possibles pour le choix
     *
     * @var array
     */
    protected $options = [];

    /**
     * Choix pondéré
     *
     * @param ChoiceData $data Données du choix
     *
     * @throws Exception si les options sont mal formatés
     */
    public function __construct(ChoiceData $data)
    {
        $this->name = $data->getName();
        $this->options = $data->getOptions();
        $this->rules = $data->getRules();
    }

    /**
     * Renvoie toutes les règles de configurations
     *
     * @return array
     * @deprecated
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Indique si une règle existe ou nom
     *
     * @param string $ruleName Nom de la règle
     * @return bool
     */
    public function hasRule(string $ruleName): bool
    {
        if (isset($this->rules[$ruleName])) {
            return true;
        }
        return false;
    }

    /**
     * Renvoie le contenu d'une règle et l'efface du choix
     *
     * @param string $ruleName Nom de la règle
     * @return mixed
     * @throws Exception Si la règle n'est pas présente
     */
    public function getRule(string $ruleName)
    {
        if (!isset($this->rules[$ruleName])) {
            throw new RuleDoesNotExist(
                $this->getName(),
                $ruleName
            );
        }

        $rule = $this->rules[$ruleName];
        unset($this->rules[$ruleName]);
        return $rule;
    }

    /**
     * Renvois les informations d'une option
     *
     * @param string $name nom de l'option demandée
     *
     * @return array
     * @throws Exception si aucune option ne répond au nom demandé
     */
    public function getOption(string $name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        throw new RuleHasNotOption(
            $this->getName(),
            $name
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
     * @param strin $name      Nom de l'option
     * @param array $newValues Nouvelles valeurs
     *
     * @return self
     * @throws Exception si aucune option ne répond au nom demandé
     */
    protected function setOption($name, $newValues)
    {
        if (!isset($this->options[$name])) {
            throw new RuleHasNotOption(
                $this->getName(),
                $name
            );
        }

        $this->options[$name] = $newValues;

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
        unset($this->options[$name]);

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
        if (!isset($this->loaded[$name])) {
            throw new RuleHasNotOption(
                $this->getName(),
                $name
            );
        }

        $this->loaded[$name] = $newValues;

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
        if (isset($this->loaded[$name])) {
            return $this->loaded[$name];
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

            $this->loaded[$temporyOption['name']] = $temporyOption;
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
     * @param string $fieldName  Nom du chanmp sur lequel chercher la valeur
     * @param string $optionName Valeur à forcer
     * @return bool/string
     */
    public function canIForce(string $fieldName = 'name', string $optionName)
    {
        $this->load();

        foreach ($this->loaded as $option) {
            if ($option[$fieldName] != $optionName) {
                continue;
            }

            if ($option['weight'] == 0) {
                return false;
            }

            return $option['name'];
        }

        return false;
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
