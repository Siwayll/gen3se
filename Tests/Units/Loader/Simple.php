<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire\Loader;

use atoum;
use Siwayll\Histoire\ChoiceData;
use Siwayll\Histoire\Choice;
use Siwayll\Histoire\Loader\Simple as TestedClass;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Simple extends atoum
{
    /**
     * Donne une liste de choix en un tableau
     *
     * @return array
     */
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

    /**
     * Donne des données de tests avec une absence de nom
     *
     * @return array
     */
    private function getChoiceWhithoutName()
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
                ],
                [
                    'options' => [
                        [
                            'name' => 'y-1',
                            'text' => 'bleu',
                            'weight' => 50,
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
                $choice = new TestedClass([]);
            })
                ->hasMessage('Choix vide')
                ->hasCode(400)
            ->object(new TestedClass($this->getChoiceWhithoutName()))
        ;
    }

    /**
     * Présence de modificateurs
     *
     * @return void
     */
    public function testHasModificator()
    {
        $this
            ->if($loader = new TestedClass($this->getChoiceOne()))
            ->boolean($loader->hasModificators())
                ->isFalse()
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
            ->if($loader = new TestedClass($this->getChoiceOne()))
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
            ->if($loader = new TestedClass($this->getChoiceOne()))
            ->and($data = $this->getChoiceOne())
            ->and($choice = new Choice(new ChoiceData($data['choices'][0])))
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
