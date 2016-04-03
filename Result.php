<?php

namespace Siwayll\Histoire;

use Siwayll\Histoire\Result\CoreInterface;
use Solire\Conf\Conf;
use Solire\Conf\Loader\ArrayToConf;

class Result
{
    private $dirty = [];
    private $storage;

    /**
     *
     * @param string $name
     * @param array  $value
     * @return self
     */
    public function addStorageRule($name, $value)
    {
        $this->stow[$name] = $value;
        return $this;
    }

    /**
     *
     * @param CoreInterface $storage
     * @return self
     */
    public function setStorage(CoreInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function getDirtyData()
    {
        return $this->dirty;
    }

    public function hasModificators()
    {
        return false;
    }

    public function rerouteData()
    {
        $this->reroute = true;
    }

    public function reset()
    {
        $this->dirty = [];
        $this->reroute = false;
    }

    /**
     * Enregistre le resultat d'un choix
     *
     * @param string $name  Nom du choix
     * @param array  $datas Données à enregistrer
     *
     * @return $this
     */
    public function saveFor($name, array $datas)
    {
        unset(
            $datas['mod'],
            $datas['weight'],
            $datas['tags'],
            $datas['name']
        );

        foreach ($datas as $key => $value) {
            if (empty($value)) {
                unset($datas[$key]);
            }
        }

        $this->dirty[] = [$name => $datas];

        if (isset($this->stow[$name])) {
            if (count($datas) == 1) {
                $datas = array_shift($datas);
            }
            // classement des données lorsqu'il y en a plusieurs
            if (is_array($datas)) {
                $datas = new ArrayToConf($datas);
            }
            if ($this->storage->has(...$this->stow[$name]) === true) {
                $alreadyPresent = $this->storage->get(...$this->stow[$name]);
                if (!is_array($alreadyPresent)) {
                    $alreadyPresent = [$alreadyPresent];
                }
                $alreadyPresent[] = $datas;
                $datas = $alreadyPresent;
            }
            $this->storage->set($datas, ...$this->stow[$name]);
        }

        return $this;
    }
}
