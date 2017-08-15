<?php

namespace Siwayll\Gen3se\Loader;

use \Exception;
use Hoa\Compiler\Llk\TreeNode;
use Siwayll\Gen3se\Choice;
use Siwayll\Gen3se\ChoiceData;
use Siwayll\Gen3se\LoaderInterface;
use Siwayll\Gen3se\RegisterTrait;

class FromCompiler implements LoaderInterface
{
    use RegisterTrait;
    private $order = [];
    private $choices = [];
    private $loaded = [];

    /**
     * Information sur l'ajout de Modificateurs
     *
     * @return bool
     */
    public function hasModificators(): bool
    {
        return false;
    }

    public function getInstructions(): array
    {
        return [];
    }

    public function __construct()
    {

    }

    public function addChoice(array $choiceData)
    {
        $this->choices[$choiceData['name']] = $choiceData;
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
    public function getChoice(string $name): Choice
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
