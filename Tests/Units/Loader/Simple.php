<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire\Loader;

use atoum;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Simple extends atoum
{
    private function getChoiceOne()
    {
        $choice = [
            'order' => ['yeux'],
            'choices' => [
                [
                    'name' => 'yeux',
                    'options' => [
                        [
                            'name' => 'y-1',
                            'text' => 'bleu',
                            'weight' => 50,
                        ],
                        [
                            'name' => 'y-2',
                            'text' => 'marron',
                            'weight' => 129,
                        ],
                        [
                            'name' => 'y-3',
                            'text' => 'vert',
                            'weight' => 20,
                        ],
                        [
                            'name' => 'y-4',
                            'text' => 'hétérochromie',
                            'weight' => 1,
                        ],
                    ]
                ]
            ]
        ];

        return $choice;
    }

// -------------------------------------------------------------------------- //

    /**
     * Instantiation d'un chargeur de données pour le scénario
     *
     * @return void
     */
    public function testConstruct()
    {
        $this
            ->exception(function () {
                $choice = new \Siwayll\Histoire\Loader\Simple([]);
            })
                ->hasMessage('Choix vide')
                ->hasCode(400)
        ;
    }

    /**
     * Contrôle chargement ordre de choix
     *
     * @return void
     */
    public function testGetOrder()
    {
        $this
            ->if($loader = new \Siwayll\Histoire\Loader\Simple($this->getChoiceOne()))
            ->array($loader->getOrder())
                ->isEqualTo(['yeux'])
        ;
    }

    /**
     * Contrôle chargement d'un choix
     *
     * @return void
     */
    public function testGetChoice()
    {
        $this
            ->if($loader = new \Siwayll\Histoire\Loader\Simple($this->getChoiceOne()))
            ->and($data = $this->getChoiceOne())
            ->and($choice = new \Siwayll\Histoire\Choice($data['choices'][0]))
            ->object($loader->getChoice('yeux'))
                ->isEqualTo($choice)
            ->object($loader->getChoice('yeux'))
                ->isEqualTo($choice)
            ->exception(function () use ($loader) {
                $loader->getChoice('cheveux');
            })
                ->hasMessage('Aucun choix n\'a le nom _cheveux_')
                ->hasCode(400)
        ;
    }
}
