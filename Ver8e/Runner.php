<?php

namespace Siwayll\Gen3se\Ver8e;

use Hoa\Compiler\Llk\Llk;
use Hoa\File\Read;

class Runner
{
    const LANGUAGE_FILE = __DIR__ . '/Ver8e.pp';
    private $compiler;

    public function __construct()
    {
        $this->compiler = Llk::load(new Read(self::LANGUAGE_FILE));
    }

    /**
     * Crée un scenario à partir du fichier donné
     *
     * @param Read $file Fichier en ver8e a éxécuter
     *
     * @return \Siwayll\Gen3se\Generator\Generic
     * @throws \Hoa\Compiler\Exception
     * @throws \Hoa\Compiler\Exception\UnexpectedToken
     */
    public function run(Read $file)
    {
        $ast = $this->compiler->parse($file->readAll());
        $scenarioLoader = new Parser();
        return $scenarioLoader->visit($ast);
    }
}