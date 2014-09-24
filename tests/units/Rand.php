<?php
/**
 * Chargement automatique des classes
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire;

use atoum;
use \Siwayll\Histoire\Rand as testedClass;

/**
 * Chargement automatique des classes
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Rand extends atoum
{
    /**
     * Contrôle d'initialisation de la classe
     *
     * @return void
     */
    public function testConstruct()
    {
        $this
            ->object(new testedClass())
                ->isInstanceOf('\Siwayll\Histoire\Rand')
            ->object(new testedClass(0))
                ->isInstanceOf('\Siwayll\Histoire\Rand')
            ->object(new testedClass(5, 15))
                ->isInstanceOf('\Siwayll\Histoire\Rand')
            ->exception(function () {
                $foo = new testedClass(5, 3);
            })
                ->hasMessage('Max doit être supérieur à min')
                ->hasCode(400)
            ->exception(function () {
                $foo = new testedClass(3);
            })
                ->hasMessage('Max doit être supérieur à min')
                ->hasCode(400)
        ;
    }

    /**
     * Enregistrement de la valeur min
     *
     * @return void
     */
    public function testSetMin()
    {
        $this
            ->if($rand = new testedClass(5, 30))
            ->object($rand->setMin(15))
                ->isIdenticalTo($rand)
            ->object($rand->setMin(0))
                ->isIdenticalTo($rand)
            ->exception(function () use ($rand) {
                $rand->setMin(0.365);
            })
                ->hasMessage('Min doit être un entier')
                ->hasCode(400)
        ;
    }

    /**
     * Contrôle rapide d'une génération aléatoire
     *
     * @return void
     */
    public function testRoll()
    {
        $this
            ->if($rand = new testedClass(5, 30))
            ->integer($rand->roll())
                ->isGreaterThanOrEqualTo(5)
                ->isLessThanOrEqualTo(30)
                ->isEqualTo($rand->getResult())
            ->if($rand->setMin(30))
            ->integer($rand->roll())
                ->isEqualTo(30)
        ;
    }
}
