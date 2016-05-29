<?php

namespace Siwayll\Gen3se;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Factory
{
    /**
     * @var LoaderInterface
     */
    private static $loader;
    private static $order;
    private static $result;
    private static $logger;

    private static $mods = [];

    /**
     * @param LoaderInterface $loader Module de chargement des choix
     */
    public static function setLoader(LoaderInterface $loader)
    {
        self::$loader = $loader;
    }
    public static function setOrder($order)
    {
        self::$order = $order;
    }
    public static function setResult($result)
    {
        self::$result = $result;
    }

    public static function addMod($mod)
    {
        self::$mods[] = $mod;
    }

    public static function loadEngine()
    {
        $varNames = ['loader', 'order', 'result'];
        foreach ($varNames as $varName) {
            ${$varName} = self::${$varName};
            if (${$varName} === null) {
                $className = __NAMESPACE__ . '\\' . ucfirst($varName);
                ${$varName} = new $className();
            }
        }

        if (self::$logger !== null) {
            $logger = self::$logger;
        } else {
            file_put_contents(__DIR__ . '/log/engine.log', '');
            $logger = new Logger('engine');
            $stream = new Streamhandler(__DIR__ . '/log/engine.log', Logger::DEBUG);
            $logger->pushHandler($stream);
            self::$logger = $logger;
        }

        $engine = new Engine($loader, $order, $result, $logger);

        foreach (self::$mods as $mod) {
            $engine->addModificator($mod);
        }

        self::reset();
        return $engine;
    }

    /**
     * Reset des données
     *
     * @return void
     */
    public static function reset()
    {
        self::$mods = [];
        self::$loader = null;
        self::$order = null;
        self::$result = null;
    }
}
