<?php

namespace tests\unit\Siwayll\Histoire\Modificator;

use atoum;
use \Siwayll\Histoire\Modificator\Mark as TestedClass;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Mark extends atoum
{
    /**
     * Contrôle de validité des informations sur les instructions
     *
     * @return void
     */
    public function testGetInstructions()
    {
        $this
            ->given($mark = new TestedClass())
            ->dump($mark->getInstructions())
            ->assert('Liste des méthodes données')
                ->array($mark->getInstructions())
                    ->hasSize(1)
                    ->hasKey('addMark')
            ->given($instructions = $mark->getInstructions())
            ->assert('Test valeur addMark')
                ->array($instructions['addMark'])
                    ->object[0]->isIdenticalTo($mark)
                    ->string[1]->isEqualTo('addMark')
        ;
    }

    /**
     * Préparation de l'objet
     *
     * @return void
     */
    public function testConstruct()
    {
        $this
            ->object(new TestedClass())
                ->isInstanceOf('Siwayll\Histoire\Modificator\Base')
        ;
    }

    /**
     * Instantiation d'un chargeur de données pour le scénario
     *
     * @return void
     */
    public function testApply()
    {
        $this
            ->if($mark = new TestedClass())
            ->variable($mark->addMark(['testMark' => 50]))
                ->isNull()
            ->array($mark->apply([]))
                ->isEqualTo([])
            ->if($option = ['marks' => ['testMark'], 'weight' => 50])
            ->array($mark->apply($option))
                ->isEqualTo(['marks' => ['testMark'], 'weight' => 100])
            ->if($option = ['marks' => ['!testMark'], 'weight' => 50])
            ->array($mark->apply($option))
                ->isEqualTo(['marks' => ['!testMark'], 'weight' => 0])
            ->if($option = ['marks' => ['#testMark'], 'weight' => 50])
            ->array($mark->apply($option))
                ->isEqualTo(['marks' => ['#testMark'], 'weight' => 150])
            ->if($option = ['marks' => ['-testMark'], 'weight' => 100])
            ->array($mark->apply($option))
                ->isEqualTo(['marks' => ['-testMark'], 'weight' => 50])
            ->if($option = ['marks' => ['-testMark'], 'weight' => 10])
            ->array($mark->apply($option))
                ->isEqualTo(['marks' => ['-testMark'], 'weight' => 0])
            ->if($option = ['marks' => ['#testMarkNoPresent'], 'weight' => 50])
            ->array($mark->apply($option))
                ->isEqualTo(['marks' => ['#testMarkNoPresent'], 'weight' => 0])

        ;
    }

    /**
     * Fonctionnement interne
     *
     * @return void
     */
    public function testAddMark()
    {
        $this
            ->if($mark = new TestedClass())
            ->variable($mark->addMark(['testMark' => 50]))
                ->isNull()
            ->array($mark->getDatas())
                ->isEqualTo(['TESTMARK' => 50])
            ->variable($mark->addMark(['testMark' => 25]))
                ->isNull()
            ->array($mark->getDatas())
            ->isEqualTo(['TESTMARK' => 75])
        ;
    }
}
