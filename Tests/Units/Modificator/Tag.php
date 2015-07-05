<?php

namespace tests\unit\Siwayll\Histoire\Modificator;

use atoum;
use \Siwayll\Histoire\Modificator\Tag as TestedClass;
use \Siwayll\Histoire\Error\Level;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Tag extends atoum
{
    /**
     * Test de la gestion des Tags
     * @return void
     */
    public function testAddTag()
    {
        $this
            ->if($tag = new TestedClass())
            ->variable($tag->addTag('testtag'))
                ->isNull()
            ->array($tag->getDatas())
                ->isEqualTo(['TESTTAG'])
            ->variable($tag->addTag(['tag1', 'tag2']))
                ->isNull()
            ->array($tag->getDatas())
                ->isEqualTo(['TESTTAG', 'TAG1', 'TAG2'])
            ->variable($tag->rmTag(['tag1', 'tag2']))
                ->isNull()
            ->array($tag->getDatas())
                ->isEqualTo(['TESTTAG'])
            ->exception(function () use ($tag) {
                $tag->addTag('NON&VALIDE');
            })
                ->hasMessage('_NON&VALIDE_ mal formaté (&!)')
                ->hasCode(Level::NOTICE)
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
            ->if($tag = new TestedClass())
            ->variable($tag->addTag(['toto']))
                ->isNull()
            ->array($tag->apply([]))
                ->isEqualTo([])
            ->if($option = ['tags' => ['TITI' => 2], 'weight' => 50])
            ->array($tag->apply($option))
                ->isEqualTo(['tags' => ['TITI' => 2], 'weight' => 50])
            ->if($option = ['tags' => ['TOTO' => 2], 'weight' => 50])
            ->array($tag->apply($option))
                ->isEqualTo(['tags' => ['TOTO' => 2], 'weight' => 100])
            ->if($option = ['tags' => ['TOTO&TATA' => 2], 'weight' => 50])
            ->array($tag->apply($option))
                ->isEqualTo(['tags' => ['TOTO&TATA' => 2], 'weight' => 50])
            ->variable($tag->addTag(['tata']))
                ->isNull()
            ->array($tag->apply($option))
                ->isEqualTo(['tags' => ['TOTO&TATA' => 2], 'weight' => 100])
            ->if($option = ['tags' => ['TOTO&!TATA' => 2], 'weight' => 50])
            ->array($tag->apply($option))
                ->isEqualTo(['tags' => ['TOTO&!TATA' => 2], 'weight' => 50])
            ->variable($tag->rmTag('tata'))
                ->isNull()
            ->array($tag->apply($option))
                ->isEqualTo(['tags' => ['TOTO&!TATA' => 2], 'weight' => 100])
        ;
    }

}
