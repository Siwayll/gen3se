<?php

namespace Siwayll\Histoire\Modificator;

use \Siwayll\Histoire\Scenari;

abstract class Base
{
    protected $engine;

    abstract public function getInstructions();
    abstract public function getName();

    abstract public function apply($options);

    /**
     *
     * @param Engine $engine
     * @return self
     */
    public function linkToEngine($engine)
    {
        $this->engine = $engine;
        return $this;
    }

}
