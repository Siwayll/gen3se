<?php

namespace Siwayll\Histoire;

use Monolog\Formatter\HtmlFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Factory
{

    private static $loader;
    private static $order;
    private static $result;
    private static $logger;

    private static $mods = [];

    public static function setLoader($loader)
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
            file_put_contents('engine.log', '');
            $logger = new Logger('engine');
            $stream = new Streamhandler('engine.log', Logger::DEBUG);
            $logger->pushHandler($stream);
        }

        $engine = new Engine($loader, $order, $result, $logger);

        foreach (self::$mods as $mod) {
            $engine->addModificator($mod);
        }

        return $engine;
    }
}
