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
class ChoiceOptionParser extends atoum
{
    private function getTreeOfFile(string $fileName)
    {
        $path = TEST_DATA_DIR . '/Ver8e/ChoiceOptionParser/' . $fileName;
        $file = new Read($path);
        $compiler = Llk::load(new Read(__DIR__ . '/../../../Ver8e/Ver8e.pp'));
        $ast = $compiler->parse($file->readAll());
        foreach ($ast->getChildren() as $element) {
            if ($element->getId() === '#choice') {
                foreach ($element->getChildren() as $child) {
                    if ($child->getId() === '#choiceOption') {
                        return $child;
                    }
                }
            }
        }
    }

    protected function namesProvider()
    {
        return [
            [$this->getTreeOfFile('simple.ver8'), 'auto_', 'option1'],
            [$this->getTreeOfFile('namedOption.ver8'), 'testName', 'option1'],
        ];
    }

    /**
     * Instantiation d'un choix
     *
     * @return void
     * @dataProvider namesProvider
     */
    public function testName($node, $name, $value)
    {
        $this
            ->if($option = $this->newTestedInstance($node, []))
            ->then
            ->array($option->get())
                ->string['name']->startWith($name)
                ->string['text']->isEqualTo($value)
        ;
    }

    protected function mainValuesProvider()
    {
        return [
            [$this->getTreeOfFile('simple.ver8'), 'text', 'option1'],
            [$this->getTreeOfFile('changedMainValue.ver8'), 'toto', 'option1'],
        ];
    }

    /**
     * Instantiation d'un choix
     *
     * @return void
     * @dataProvider mainValuesProvider
     */
    public function testMainValue($node, $name, $value)
    {
        $this
            ->if($option = $this->newTestedInstance($node, []))
            ->then
            ->array($option->get())
                ->hasKey($name)
                ->string[$name]->isEqualTo($value)
        ;
    }


    protected function valuesProvider()
    {
        return [
            [$this->getTreeOfFile('simple.ver8'), ['text' => 'option1']],
            [$this->getTreeOfFile('changedMainValue.ver8'), ['toto' => 'option1']],
            [$this->getTreeOfFile('complexValues.ver8'), ['mainValue' => 'option1', 'opt1' =>'Lorem ipsum', 'opt2' => 'MoreData']],
            [$this->getTreeOfFile('complexEmptyValues.ver8'), ['text' => 'non', 'digestStr' =>'']],
        ];
    }

    /**
     * Instantiation d'un choix
     *
     * @return void
     * @dataProvider valuesProvider
     */
    public function testValue($node, $values)
    {
        $this
            ->if($option = $this->newTestedInstance($node, []))
        ;
        foreach ($values as $name => $value) {
            $this
                ->array($option->get())
                    ->hasKey($name)
                    ->variable[$name]->isEqualTo($value)
            ;
        }
    }
}
