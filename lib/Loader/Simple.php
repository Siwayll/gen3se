<?php

namespace Siwayll\Gen3se\Loader;

use \Exception;
use Siwayll\Gen3se\Choice;
use Siwayll\Gen3se\ChoiceData;

class Simple
{
    private $order = [];
    private $choices = [];
    private $loaded = [];

    /**
     * Information sur l'ajout de Modificateurs
     *
     * @return boolean
     */
    public function hasModificators()
    {
        return false;
    }

    public function __construct(array $data)
    {
        if (empty($data)) {
            throw new Exception('Choix vide', 400);
        }

        $this->order = $data['order'];
        foreach ($data['choices'] as $choice) {
            if (!isset($choice['name'])) {
                continue;
            }
            $this->choices[$choice['name']] = $choice;
        }
    }

    /**
     * Renvois l'ordre de traitement des choix
     *
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Charge un choix
     *
     * @param string $name Nom du choix
     *
     * @return Choice
     * @throws Exception si aucun choix n'éxiste avec ce nom
     */
    public function getChoice($name)
    {
        if (isset($this->loaded[$name])) {
            return $this->loaded[$name];
        }

        if (isset($this->choices[$name])) {
            $choiceData = new ChoiceData($this->choices[$name]);
            $this->loaded[$name] = new Choice($choiceData);
            return $this->loaded[$name];
        }

        throw new Exception('Aucun choix n\'a le nom _' . $name . '_', 400);
    }
}
