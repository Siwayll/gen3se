<?php
namespace Gen3se\Engine;

use \Exception;
use Gen3se\Engine\Register;

/**
 * Données pour Choice
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class ChoiceData
{
    /**
     * Nom identifiant le choix
     *
     * @var string
     */
    protected $name = '';

    /**
     * Liste des options du choix
     *
     * @var array
     */
    protected $options = [];

    /**
     * Nom des colonnes obligatoires pour caractériser une option du choix
     *
     * @var string[]
     */
    private $requiredColumns = ['name', 'weight'];

    /**
     * Règles à appliquer à toutes les options
     *
     * @var Array
     */
    private $globalRules = [];

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

        if (!isset($config['name']) || empty($config['name'])) {
            throw new Exception('Utilisation d\'un choix sans nom impossible.', 400);
        }
        $this->name = $config['name'];

        if (isset($config['globalRules'])) {
            $this->globalRules = $config['globalRules'];
        }

        if (!isset($config['options']) || empty($config['options'])) {
            throw new Exception('Le choix _' . $this->name . '_ doit avoir des options.', 400);
        }
        $this->loadOptions($config['options']);



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
     * Chargement des options
     *
     * @param array $options
     *
     * @return self
     * @throws Exception
     */
    private function loadOptions(array $options)
    {
        foreach ($options as $key => $option) {
            $option = $this->prepareOption($option, $key);
            $this->controlOption($option, $key);
            $this->options[$option['name']] = $option;
        }

        return $this;
    }

    /**
     * Formate une option
     *
     * Gère le cas ou le nom n'est pas spécifié et le récupère dans
     * l'identifiant du tableau $key
     *
     * Récupère les paramétrages globaux pour les options
     *
     * @param array        $option Informations sur l'option
     * @param string|null  $key    Clé dans le tableau des options
     *
     * @return array
     */
    private function prepareOption(array $option, $key = null)
    {
        $option = array_merge_recursive($this->globalRules, $option);

        if (!isset($option['name']) || $option['name'] == '') {
            $option['name'] = $key;
        }

        return $option;
    }

    /**
     * Contrôle du format des options
     *
     * @param array $option Option à contrôler
     * @param int   $key    Place de l'option dans le choix
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

    public function getOptions()
    {
        return $this->options;
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
