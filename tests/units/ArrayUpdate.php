<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire;

use atoum;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class ArrayUpdate extends atoum
{
    private function getArrayOne()
    {
        return [
            'numeric' => 15,
            'text' => 'Lorem ipsum.',
            'word' => 'fuuu',
        ];
    }


    public function testConstruct()
    {
        $this
            ->exception(function () {
                $foo = new \Siwayll\Histoire\ArrayUpdate([]);
            })
                ->hasMessage('Un tableau non vide est nécessaire')
                ->hasCode(400)
            ->object(new \Siwayll\Histoire\ArrayUpdate(['tata', 'toto', 'tutu']))
            ->object(new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
        ;
    }

    /**
     * Contrôle du getter
     *
     * @return void
     */
    public function testGet()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->string($array->get('text'))
               ->isEqualTo('Lorem ipsum.')
            ->exception(function () use ($array) {
                $array->get('blablo');
            })
                ->hasCode(400)
                ->hasMessage('__blablo__ n\'existe pas')

        ;
    }

    /**
     * Contrôle de l'incrémentation d'une valeur
     *
     * @return void
     */
    public function testIncrement()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->object($array->increment('numeric'))
                ->isIdenticalTo($array)
            ->integer($array->get('numeric'))
                ->isEqualTo(16)
             ->object($array->increment('numeric', 15))
                ->isIdenticalTo($array)
            ->integer($array->get('numeric'))
            ->isEqualTo(31)
            ->exception(function () use ($array) {
                $array->increment('text');
            })
                ->hasCode(400)
                ->hasMessage('__text__ n\'est pas de type numérique')
            ->exception(function () use ($array) {
                $array->increment('blabla');
            })
                ->hasCode(400)
                ->hasMessage('__blabla__ n\'existe pas')
        ;
    }

    /**
     * Contrôle de la décrémentation d'une valeur
     *
     * @return void
     */
    public function testDecrement()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->object($array->decrement('numeric'))
                ->isIdenticalTo($array)
            ->integer($array->get('numeric'))
                ->isEqualTo(14)
             ->object($array->decrement('numeric', 15))
                ->isIdenticalTo($array)
            ->integer($array->get('numeric'))
            ->isEqualTo(-1)
            ->exception(function () use ($array) {
                $array->decrement('text');
            })
                ->hasCode(400)
                ->hasMessage('__text__ n\'est pas de type numérique')
            ->exception(function () use ($array) {
                $array->decrement('blabla');
            })
                ->hasCode(400)
                ->hasMessage('__blabla__ n\'existe pas')
        ;
    }
}
