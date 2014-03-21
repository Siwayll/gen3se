<?php
/**
 * Chargement automatique des classes
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire;

use atoum;

/**
 * Chargement automatique des classes
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Rand extends atoum
{
    public function testConstruct()
    {
        $this
            ->object(new \Siwayll\Histoire\Rand())
                ->isInstanceOf('\Siwayll\Histoire\Rand')
            ->object(new \Siwayll\Histoire\Rand(0))
                ->isInstanceOf('\Siwayll\Histoire\Rand')
            ->object(new \Siwayll\Histoire\Rand(5, 15))
                ->isInstanceOf('\Siwayll\Histoire\Rand')
            ->exception(function() {
                $foo = new \Siwayll\Histoire\Rand(5, 3);
            })
                ->hasMessage('Max doit être supérieur à min')
                ->hasCode(400)
            ->exception(function() {
                $foo = new \Siwayll\Histoire\Rand(3);
            })
                ->hasMessage('Max doit être supérieur à min')
                ->hasCode(400)
        ;
    }

    public function testSetMin()
    {
        $this
            ->if($rand = new \Siwayll\Histoire\Rand(5, 30))
            ->object($rand->setMin(15))
                ->isIdenticalTo($rand)
            ->object($rand->setMin(0))
                ->isIdenticalTo($rand)
            ->exception(function() use ($rand) {
                $rand->setMin(0.365);
            })
                ->hasMessage('Min doit être un entier')
                ->hasCode(400)
        ;
    }
}