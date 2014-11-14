<?php

namespace Siwayll\Histoire\Loader;

use \Exception;
use Siwayll\Histoire\Choice;

class MultiFiles
{
    private $order = [];
    private $loaded = [];

    public function __construct($dirPath, array $order)
    {
        $this->order = $order;
        $this->dirPath = $dirPath;
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

        $fileName = $this->dirPath . DIRECTORY_SEPARATOR . $name . '.yml';
        if (file_exists($fileName)) {
            $this->loaded[$name] = new Choice(yaml_parse_file($fileName));
            return $this->loaded[$name];
        }

        throw new Exception('Aucun choix n\'a le nom _' . $name . '_', 400);
    }
}
