<?php

namespace Siwayll\Gen3se;

/**
 * Interface LoaderInterface
 * @package Siwayll\Gen3se
 */
interface LoaderInterface
{
    /**
     * Information sur l'ajout de Modificateurs
     *
     * @return bool
     */
    public function hasModificators(): bool;

    /**
     * Liste les fonctionnalités a ajouter au Générateur
     *
     * @return array
     */
    public function getInstructions(): array;

    /**
     * Donne l'identifiant du loader dans le Registre
     *
     * @return string
     */
    public function getRegisterKey(): string;

    /**
     * Charge un choix
     *
     * @param string $name Nom du choix
     *
     * @return Choice
     */
    public function getChoice(string $name): Choice;
}