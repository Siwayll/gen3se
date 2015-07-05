<?php

namespace Siwayll\Histoire;

Trait RegisterTrait
{
    protected $registerKey = '';
    protected $registerPrefix = '';

    /**
     * Renseigne l'identifiant unique de l'objet pour le Registre
     *
     * @return self
     */
    protected function generateRegisterKey()
    {
        $this->registerKey = uniqid($this->registerPrefix);

        return $this;
    }

    /**
     * Renvoi l'identifiant du Registre
     *
     * @return string
     */
    public function getRegisterKey()
    {
        return $this->registerKey;
    }

    /**
     * Enregistre l'objet dans le registre
     *
     * @return self
     */
    protected function saveToRegister()
    {
        Register::save($this->registerKey, $this);

        return $this;
    }

    /**
     * Supprime l'objet du registre
     */
    public function __destruct()
    {
        Register::del($this->registerKey);
    }
}
