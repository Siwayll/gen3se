<?php

namespace Siwayll\Gen3se\Generator;

use Siwayll\Gen3se\Factory;
use Siwayll\Gen3se\RegisterTrait;

use Siwayll\Gen3se\Modificator\Data;

use Siwayll\Gen3se\Result\Core;
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
    public function __construct($name, $loader, $list)
    {
        // Paramétrage du loader
        Factory::setLoader($loader);
        $this->keys['loader'] = $loader->getRegisterKey();

        // Chargement des mod
        Factory::addMod(new Data());

        $this->engine = Factory::loadEngine();


        $this->order = $this->engine->getOrder();
        $this->result = $this->engine->getResult();

        // Création de l'espace de stockage du pnj
        $this->data = new Core();
        $this->result->setStorage($this->data);


        // temporaire
        $this->list = $list;


        $this->data->set(uniqId($name . '_'), '__data');
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
