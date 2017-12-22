<?php

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\OptionCleanerTrait;
use Gen3se\Engine\Result\Core;
use Gen3se\Engine\Result\CoreInterface;
use Solire\Conf\Conf;
use Solire\Conf\Loader\ArrayToConf;

class Result
{
    private $dirty = [];
    private $storage;

    use OptionCleanerTrait;

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
     *
     * @param $conf
     * @param $data
     * @return Core
     */
    protected function arrayConvert($conf, $data)
    {
        if ($conf === null) {
            $conf = new Core();
        }
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $conf->set($this->arrayConvert(null, $value), $key);
                continue;
            }
            $conf->set($value, $key);
        }
        return $conf;
    }

    /**
     * Enregistre le resultat d'un choix
     *
     * @param string $name  Nom du choix
     * @param array  $datas Données à enregistrer
     *
     * @return $this
     */
    public function saveFor($name, array $datas, array $rules = null)
    {
        $datas = $this->cleanOption($datas);

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
                $datas = $this->arrayConvert(null, $datas);
            }
            if ($this->storage->has(...$this->stow[$name]) === true) {
                $alreadyPresent = $this->storage->get(...$this->stow[$name]);
                if (!is_array($alreadyPresent)) {
                    $alreadyPresent = [$alreadyPresent];
                }
                $alreadyPresent[] = $datas;
                $datas = $alreadyPresent;
            }
            if (!empty($rules)) {
                if (!is_array($datas)) {
                    $datas = [$datas];
                }
                $datas['_rules'] = $rules;
            }
            $this->storage->set($datas, ...$this->stow[$name]);
        }

        return $this;
    }
}
