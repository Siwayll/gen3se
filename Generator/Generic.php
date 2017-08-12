<?php

namespace Siwayll\Gen3se\Generator;

use Siwayll\Gen3se\Choice;
use Siwayll\Gen3se\Constraint;
use Siwayll\Gen3se\Factory;
use Siwayll\Gen3se\LoaderInterface;
use Siwayll\Gen3se\RegisterTrait;

use Siwayll\Gen3se\Modificator\Data;
use Siwayll\Gen3se\Modificator\NameSound;
use Siwayll\Gen3se\Modificator\Tag;

use Siwayll\Gen3se\Result\Core;
use Siwayll\Gen3se\Ver8e\ModList;
use Solire\Conf\Conf;

class Generic
{
    use RegisterTrait;

    private $keys = [];

    /**
     * @var Conf
     */
    protected $constraint;

    /**
     *
     * @var Engine
     */
    protected $engine;

    /**
     * Générateur
     */
    public function __construct($name, LoaderInterface $loader, $list, $render = null, ModList $modList = null)
    {
        // Paramétrage du loader
        Factory::setLoader($loader);
        $this->keys['loader'] = $loader->getRegisterKey();

        // Chargement des mod
        if ($modList !== null) {
            $mods = $modList->getList();
            foreach ($mods as $mod) {
                Factory::addMod(new $mod());
            }
        }

        $this->engine = Factory::loadEngine();


        $this->order = $this->engine->getOrder();
        $this->result = $this->engine->getResult();

        // Création de l'espace de stockage du pnj
        $this->data = new Core();
        $this->result->setStorage($this->data);
        $this->render = $render;


        // temporaire
        $this->list = $list;


        $this->data->set(uniqId($name . '_'), '__data');
    }

    /**
     * Accès au choix demandé
     *
     * @param $choiceName Nom du choix
     * @return Choice
     */
    public function getChoice($choiceName): Choice
    {
        return $this->engine->getLoader()->getChoice($choiceName);
    }

    /**
     * Formation du rendu du scenario
     *
     * @return bool|string
     */
    public function render()
    {
        if ($this->render === null) {
            return false;
        }

        $engine = new \Mustache_Engine();
        $engine->addHelper('case', [
            'lower' => function($value) { return strtolower((string) $value); },
            'upper' => function($value) { return strtoupper((string) $value); },
        ]);

        $toArray = function ($conf) use (&$toArray) {
            $conf = (array) $conf;
            foreach ($conf as &$data) {
                if (is_object($data) || is_array($data)) {
                    $data = $toArray($data);
                }
            }
            return $conf;
        };

        $arrayData = $toArray($this->data);

        foreach ($arrayData as $key => $value) {
            if (is_array($value)) {
                if (!isset($value['text'])) {
                    $arrayData[$key] = '';
                    continue;
                }
                $arrayData[$key] = $value['text'];
            }
        }

        return $engine->render($this->render, $arrayData);
    }

    /**
     * Accède aux modificateurs utilisés par le Moteur
     *
     * @param string $modName Nom du Modificateur
     * @return object
     */
    public function getModificator(string $modName)
    {
        return $this->engine->getModificator($modName);
    }

    /**
     * @param Constraint $constraint
     * @return void
     */
    public function addConstraint(Constraint $constraint)
    {
        $this->engine->addConstraint($constraint);
    }

    /**
     * Charge le moteur avec les choix listés et les résoux
     *
     * @param string $orders Liste des choix
     *
     * @return self
     */
    private function resolve(...$orders)
    {
        $this->order->addAtEnd($orders);
        $this->engine->resolveAll();

        return $this;
    }

    /**
     * Renvoie le resultat de la génération
     *
     * @return Solire\Conf
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Résolution du scénario
     *
     * @return self
     */
    public function load()
    {
        $this->resolve(...$this->list);

        return $this;
    }
}
