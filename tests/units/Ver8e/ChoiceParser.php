<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Gen3se\Ver8e;

use atoum;
use Hoa\Compiler\Llk\Llk;
use Hoa\File\Read;
use Hoa\Visitor\Element;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class ChoiceParser extends atoum
{
    private function getTreeOfFile(string $fileName)
    {
        $path = TEST_DATA_DIR . '/Ver8e/ChoiceParser/' . $fileName;
        $file = new Read($path);
        $compiler = Llk::load(new Read(__DIR__ . '/../../../Ver8e/Ver8e.pp'));
        $ast = $compiler->parse($file->readAll());
        return $this->visit($ast);
    }

    private function visit(Element $globalElement)
    {
        foreach ($globalElement->getChildren() as $element) {
            if ($element->getId() === '#choice') {
                    return $element;
            }
        }
    }

    /**
     * Instantiation d'un choix
     *
     * @return void
     */
    public function testGetName()
    {
        $this
            ->if($choice = $this->newTestedInstance($this->getTreeOfFile('simple.ver8')))
            ->then
            ->string($choice->getName())
                ->isEqualTo('qsn001')
            ->array($choice->get())
                ->string['name']->isEqualTo('qsn001')
        ;
    }

    public function testStorageRules()
    {
        $this
            ->if($choice = $this->newTestedInstance($this->getTreeOfFile('simple.ver8')))
            ->then
            ->array($choice->get())
                ->array['storageRule']
                    ->isEqualTo(['050-qsn001'])

            ->if($choice = $this->newTestedInstance($this->getTreeOfFile('complexStorage.ver8')))
            ->then
            ->array($choice->get())
                ->array['storageRule']
                    ->isEqualTo(['050-qsn001', 'subCat001', 'data'])
        ;
    }

    public function testGlobal()
    {
        $this
            ->if($choice = $this->newTestedInstance($this->getTreeOfFile('simple.ver8')))
            ->then
            ->array($choice->debugGetGlobal())
                ->hasKey('consume')
                ->string['render']
                    ->isEqualTo('toto')
        ;
    }
}
