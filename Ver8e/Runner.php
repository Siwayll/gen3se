<?php

namespace Siwayll\Gen3se\Ver8e;

use Hoa\Compiler\Llk\Llk;
use Hoa\File\Read;

class Runner
{
    const LANGUAGE_FILE = __DIR__ . '/Ver8e.pp';
    private $compiler;
    private $parser;

    public function __construct()
    {
        $this->compiler = Llk::load(new Read(self::LANGUAGE_FILE));
        $this->parser = new Parser();
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
        return $this->parser->visit($ast);
    }

    public function addChoice(Read $file)
    {
        $ast = $this->compiler->parse($file->readAll());
        $this->parser->compileChoices($ast);
    }
}