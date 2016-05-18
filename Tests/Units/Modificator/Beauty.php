<?php

namespace tests\unit\Siwayll\Gen3se\Modificator;

use atoum;
use \Siwayll\Gen3se\Modificator\Beauty as TestedClass;
use \Siwayll\Gen3se\Error\Level;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Beauty extends atoum
{
    /**
     * Test de la gestion des Tags
     * @return void
     */
    public function testVarBeauty()
    {
        $this
            ->if($beauty = new TestedClass())
            ->variable($beauty->add('base +1000'))
                ->isNull()
            ->array($beauty->getDatas())
                ->isEqualTo(['value' => 1000, 'history' => ['base +1000']])
            ->variable($beauty->add('yeux:blessure -500'))
                ->isNull()
            ->array($beauty->getDatas())
                ->isEqualTo(['value' => 500, 'history' => ['base +1000', 'yeux:blessure -500']])
            ->exception(function () use ($beauty) {
                $beauty->add('d4 #5000');
            })
                ->hasMessage('__beauty__ _d4 #5000_ mal formaté')
                ->hasCode(Level::NOTICE)
        ;
    }

    public function testBeautySmooth()
    {
        $this
            ->if($beauty = new TestedClass())
            ->variable($beauty->add('base +80000'))
                ->isNull()
            ->array($beauty->getDatas())
                ->isEqualTo(['value' => 80000, 'history' => ['base +80000']])
            ->variable($beauty->add('base ~2'))
                ->isNull()
            ->array($beauty->getDatas())
                ->isEqualTo(['value' => 40500, 'history' => ['base +80000', 'base ~2']])
        ;
        $this
            ->if($beauty = new TestedClass())
            ->variable($beauty->add('base -5000'))
                ->isNull()
            ->array($beauty->getDatas())
                ->isEqualTo(['value' => -5000, 'history' => ['base -5000']])
            ->variable($beauty->add('base ~2'))
                ->isNull()
            ->array($beauty->getDatas())
                ->isEqualTo(['value' => -3000, 'history' => ['base -5000', 'base ~2']])
        ;
    }

    public function testLitteral()
    {

        $this
            ->if($beauty = new TestedClass())
            ->variable($beauty->add('base +80000'))
                ->isNull()
            ->string($beauty->getLitteral())
                ->isEqualTo('Beauté divine')
        ;
    }
}
