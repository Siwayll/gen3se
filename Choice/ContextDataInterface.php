<?php

namespace Siwayll\Histoire\Choice;


interface ContextDataInterface
{
    /**
     * Demande d'informations sur le contexte du choix par l'Engine
     *
     * @return bool
     */
    public function wantContextData();

    /**
     * Récupération des données de contexte
     *
     * @param mixed $data Données fournies par l'Engine
     *
     * @return void
     */
    public function setContextData($data);
}
