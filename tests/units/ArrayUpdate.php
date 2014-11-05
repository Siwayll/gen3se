<?php
/**
 * Tests unitaire pour ArrayUpdate
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire;

use atoum;

/**
 * Tests unitaire pour ArrayUpdate
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class ArrayUpdate extends atoum
{
    /**
     * Renvois des données de test
     * 
     * @return Array
     */
    private function getArrayOne()
    {
        return [
            'numeric' => 15,
            'text' => 'Lorem ipsum.',
            'word' => 'fuuu',
            'array' => [],
        ];
    }

    /**
     * Contrôle initialisation class
     *
     * @return void
     */
    public function testConstruct()
    {
        $this
            ->exception(function () {
                new \Siwayll\Histoire\ArrayUpdate([]);
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
     * Contrôle de l'ajout d'un champ
     *
     * @return void
     */
    public function testAdd()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->object($array->exec(['_add' => ['textGenial' => 'blabla']]))
                ->isIdenticalTo($array)
            ->string($array->get('textGenial'))
               ->isEqualTo('blabla')
            ->object($array->exec(['_add' => ['array' => ['toto' => 'tata']]]))
                ->isIdenticalTo($array)
            ->array($array->get('array'))
                ->isEqualTo(['toto' => 'tata'])
            ->exception(function () use ($array) {
                $array->exec(['_add' => ['textGenial' => 'blabla']]);
            })
                ->hasCode(400)
                ->hasMessage('__textGenial__ existe déjà')
        ;
    }

    /**
     * Contrôle de la suppression d'un champ
     *
     * @return void
     */
    public function testDelete()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->object($array->exec(['_unset' => 'text']))
                ->isIdenticalTo($array)
            ->exception(function () use ($array) {
                $array->get('text');
            })
                ->hasCode(400)
                ->hasMessage('__text__ n\'existe pas')
            ->exception(function () use ($array) {
                $array->exec(['_unset' => ['textGenial' => null]]);
            })
                ->hasCode(400)
                ->hasMessage('__textGenial__ n\'existe pas')
        ;
    }

    /**
     * Contrôle de l'enregistrement d'une valeur
     *
     * @return void
     */
    public function testSet()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->object($array->exec(['_set' => ['text' => 'blabla']]))
                ->isIdenticalTo($array)
            ->string($array->get('text'))
               ->isEqualTo('blabla')
            ->object($array->exec(['_set' => 'text']))
                ->isIdenticalTo($array)
            ->string($array->get('text'))
               ->isEqualTo('')
            ->exception(function () use ($array) {
                $array->exec(['_set' => ['blablo' => 'blabla']]);
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
            ->object($array->exec(['_inc' => 'numeric']))
                ->isIdenticalTo($array)
            ->integer($array->get('numeric'))
                ->isEqualTo(16)
             ->object($array->exec(['_inc' => ['numeric' => 15]]))
                ->isIdenticalTo($array)
            ->integer($array->get('numeric'))
            ->isEqualTo(31)
            ->exception(function () use ($array) {
                $array->exec(['_inc' => 'text']);
            })
                ->hasCode(400)
                ->hasMessage('__text__ n\'est pas de type numérique')
            ->exception(function () use ($array) {
                $array->exec(['_inc' => 'blabla']);
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
            ->object($array->exec(['_dec' => 'numeric']))
                ->isIdenticalTo($array)
            ->integer($array->get('numeric'))
                ->isEqualTo(14)
             ->object($array->exec(['_dec' => ['numeric' => 15]]))
                ->isIdenticalTo($array)
            ->integer($array->get('numeric'))
            ->isEqualTo(-1)
            ->exception(function () use ($array) {
                $array->exec(['_dec' => 'text']);
            })
                ->hasCode(400)
                ->hasMessage('__text__ n\'est pas de type numérique')
            ->exception(function () use ($array) {
                $array->exec(['_dec' => 'blabla']);
            })
                ->hasCode(400)
                ->hasMessage('__blabla__ n\'existe pas')
        ;
    }

    /**
     * Contrôle de l'ajout en fin de champ
     * 
     * @return void
     */
    public function testAppend()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->object($array->exec(['_app' => ['text' => 'tata']]))
                ->isIdenticalTo($array)
            ->string($array->get('text'))
                ->isEqualTo('Lorem ipsum.tata')
            ->if($array->exec(['_app' => ['numeric' => 'toto']]))
            ->string($array->get('numeric'))
                ->isEqualTo('15toto')
            ->exception(function () use ($array) {
                $array->exec(['_app' => ['blabla' => 'toto']]);
            })
                ->hasCode(400)
                ->hasMessage('__blabla__ n\'existe pas')
        ;
    }

    /**
     * Contrôle du renomage d'un champ
     *
     * @return void
     */
    public function testRename()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->object($array->exec(['_rename' => ['text' => 'tata']]))
            ->string($array->get('tata'))
                ->isEqualTo('Lorem ipsum.')
            ->exception(function () use ($array) {
                $array->get('text');
            })
                ->hasCode(400)
                ->hasMessage('__text__ n\'existe pas')
            ->exception(function () use ($array) {
                $array->exec(['_rename' => ['numeric' => ['toto']]]);
            })
                ->hasCode(400)
                ->hasMessage('le nouveau nom de __numeric__ n\'est pas une chaine')
            ->exception(function () use ($array) {
                $array->exec(['_rename' => ['numeric' => 8]]);
            })
                ->hasCode(400)
                ->hasMessage('le nouveau nom de __numeric__ n\'est pas une chaine')
        ;

    }

    /**
     * Contrôle récupération du tableau
     *
     * @return void
     */
    public function testGetAll()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->array($array->getAll())
                ->isEqualTo($this->getArrayOne())
        ;
    }

    /**
     * Contrôle des retours erreur de exec
     * 
     * @return void
     */
    public function testExec()
    {
        $this
            ->if($array = new \Siwayll\Histoire\ArrayUpdate($this->getArrayOne()))
            ->exception(function () use ($array) {
                $array->exec(['blabla' => ['text' => 'fin']]);
            })
                ->hasCode(600)
                ->hasMessage('__blabla__ n\'est pas une instruction valide')
            ->object($array->exec(['_inc' => ['numeric' => 8]]))
                ->isIdenticalTo($array)
            ->object($array->exec(['_app' => ['text' => 'tata', 'word' => 'bar']]))
                ->isIdenticalTo($array)
            ->string($array->get('text'))
                ->isEqualTo('Lorem ipsum.tata')
            ->string($array->get('word'))
                ->isEqualTo('fuuubar')
        ;
    }
}
