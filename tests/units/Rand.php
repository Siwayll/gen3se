<?php
/**
 * Chargement automatique des classes
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace Tests\Unit\Siwayll\Gen3se;

use atoum;

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
            ->object($this->newTestedInstance())
            ->isInstanceOf('\Siwayll\Gen3se\Rand')
            ->object($this->newTestedInstance(0))
            ->isInstanceOf('\Siwayll\Gen3se\Rand')
            ->object($this->newTestedInstance(5, 15))
            ->isInstanceOf('\Siwayll\Gen3se\Rand')
            ->exception(function () {
                $foo = $this->newTestedInstance(5, 3);
            })
            ->hasMessage('Max doit être supérieur à min')
            ->hasCode(400)
            ->exception(function () {
                $foo = $this->newTestedInstance(3);
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
            ->if($rand = $this->newTestedInstance(5, 30))
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
            ->if($rand = $this->newTestedInstance(5, 30))
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
