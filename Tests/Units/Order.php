<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire;

use atoum;
use \Siwayll\Histoire\Order as TestedClass;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Order extends atoum
{
    /**
     * Contrôle du getter
     *
     * @return void
     */
    public function testGetAndSet()
    {
        $this
            ->if($order = new TestedClass())
            ->object($order->addAtEnd('tauk/carrure'))
                ->isIdenticalTo($order)
            ->string($order->getNext())
                ->isEqualTo('tauk/carrure')
            ->variable($order->getNext())
                ->isNull()
            ->object($order->addAtEnd('tauk/sexe'))
                ->isIdenticalTo($order)
            ->object($order->addAtEnd('tauk/carrure'))
                ->isIdenticalTo($order)
            ->string($order->getNext())
                ->isEqualTo('tauk/sexe')
            ->string($order->getNext())
                ->isEqualTo('tauk/carrure')
            ->variable($order->getNext())
                ->isNull()
            ->object($order->addAtEnd(['tauk/carrure', 'tauk/sexe', 'tauk/nom']))
                ->isIdenticalTo($order)
            ->string($order->getNext())
                ->isEqualTo('tauk/carrure')
            ->object($order->addFurther('tauk/designation'))
                ->isIdenticalTo($order)
            ->string($order->getNext())
                ->isEqualTo('tauk/designation')
            ->string($order->getNext())
                ->isEqualTo('tauk/sexe')
        ;
    }

    public function testModificators()
    {
        $this
            ->if($order = new TestedClass())
            ->boolean($order->hasModificators())
                ->isTrue()
            ->array($order->getInstructions())
                ->isNotEmpty()
        ;

    }

    public function testHasNext()
    {
        $this
            ->if($order = new TestedClass())
            ->boolean($order->hasNext())
                ->isFalse()
            ->object($order->addAtEnd('tauk/carrure'))
                ->isIdenticalTo($order)
            ->boolean($order->hasNext())
                ->isTrue()
        ;
    }
}
