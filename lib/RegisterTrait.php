<?php

namespace Siwayll\Gen3se;

Trait RegisterTrait
{
    protected $registerKey = '';
    protected $registerPrefix = '';

    /**
     * Spéficie le préfice des clés registre de la classe
     *
     * @param string $prefix Préfixe des clés pour le Registre
     *
     * @return self
     */
    protected function setPrefixForRegisterKey($prefix)
    {
        $this->registerPrefix = (string) $prefix;
        return $this;
    }


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
    final public function getRegisterKey(): string
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
