<?php

namespace tests\unit\Siwayll\Histoire;

use atoum;
use \Siwayll\Histoire\Register as TestedClass;

/**
 * Test class for Register
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Register extends atoum
{
    /**
     * Contrôle du getter
     *
     * @return void
     */
    public function testGetAndSet()
    {
        $this
            ->if(TestedClass::save('toto', 'tata'))
            ->string(TestedClass::load('toto'))
                ->isEqualTo('tata')
            ->if(TestedClass::del('toto'))
            ->exception(function() {
                TestedClass::load('toto');
            })
                ->hasMessage('_toto_ n\'est pas enregistré dans le registre')
        ;
    }
}
