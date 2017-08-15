<?php

namespace tests\unit\Siwayll\Gen3se;

use atoum;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class ChoiceData extends atoum
{
    /**
     * Renvoie un choix simple
     *
     * @return array
     */
    private function getChoiceOne()
    {
        $choice = [
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
        ];

        return $choice;
    }

    /**
     * Renvoie un choix avec un paramétrage global
     *
     * @return array
     */
    private function getChoiceWithRules()
    {
        $choice = [
            'name' => 'yeux',
            'storageRule' => ['toto', 'tata'],
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
        ];

        return $choice;
    }

    /**
     * Instantiation d'un choix
     *
     * @return void
     */
    public function testConstruct()
    {
        $this
            ->assert('Formatage incorrecte des données')
                ->exception(function () {
                    $data = $this->getChoiceOne();
                    unset($data['name']);
                    $this->newTestedInstance($data);
                })
                    ->hasMessage('Utilisation d\'un choix sans nom impossible.')
                    ->hasCode(400)
                ->exception(function () {
                    $this->newTestedInstance([]);
                })
                    ->hasMessage('L\'architecture du choix doit être un tableau non vide.')
                    ->hasCode(400)
                ->exception(function () {
                    $data = $this->getChoiceOne();
                    unset($data['options']);
                    $this->newTestedInstance($data);
                })
                    ->hasMessage('Le choix _yeux_ doit avoir des options.')
                    ->hasCode(400)
                ->exception(function () {
                    $data = $this->getChoiceOne();
                    unset($data['options'][0]['name']);
                    $this->newTestedInstance($data);
                })
                    ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                    ->hasCode(400)
                ->exception(function () {
                    $data = $this->getChoiceOne();
                    $data['options'][0]['name'] = '';
                    $this->newTestedInstance($data);
                })
                    ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                    ->hasCode(400)
                ->exception(function () {
                    $data = $this->getChoiceOne();
                    $data['options'][0]['name'] = null;
                    $this->newTestedInstance($data);
                })
                    ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                    ->hasCode(400)
                ->exception(function () {
                    $data = $this->getChoiceOne();
                    unset($data['options'][1]['weight']);
                    $this->newTestedInstance($data);
                })
                    ->hasMessage('Dans _yeux_ __weight__ est manquant pour _y-2_')
                    ->hasCode(400)

        ;
    }

    /**
     * Récupération du nom du choix
     *
     * @return void
     */
    public function testGetName()
    {
        $this
            ->if($choice = $this->newTestedInstance($this->getChoiceOne()))
            ->string($choice->getName())
                ->isEqualTo('yeux')
            ->assert('formatage du nom du choix')
                ->exception(function () {
                    $data = $this->getChoiceOne();
                    $data['name'] = '';
                    $this->newTestedInstance($data);
                })
                    ->hasMessage('Utilisation d\'un choix sans nom impossible.')
                    ->hasCode(400)
        ;
    }

    /**
     * @return array
     */
    public function getOptionsDataProvider()
    {
        return [
            [[
                'name' => 'yeux',
                'options' => [
                    [
                        'name' => 'y-1',
                        'key' => 'toto',
                        'weight' => 50,
                    ],
                    [
                        'name' => 'y-2',
                        'key' => 'toto',
                        'weight' => 129,
                    ],
                    [
                        'name' => 'y-3',
                        'key' => 'toto',
                        'weight' => 20,
                    ],
                    [
                        'name' => 'y-4',
                        'key' => 'toto',
                        'weight' => 1,
                    ],
                ]
            ]],
            [[
                'name' => 'yeux',
                'options' => [
                    'y-1' => [
                        'weight' => 50,
                        'key' => 'toto',
                    ],
                    'y-2' => [
                        'weight' => 129,
                        'key' => 'toto',
                    ],
                    'y-3' => [
                        'weight' => 20,
                        'key' => 'toto',
                    ],
                    'y-4' => [
                        'weight' => 1,
                        'key' => 'toto',
                    ],
                ]
            ]],
            [[
                'name' => 'yeux',
                'globalRules' => [
                    'key' => 'toto',
                ],
                'options' => [
                    'y-1' => [
                        'weight' => 50,
                    ],
                    'y-2' => [
                        'weight' => 129,
                    ],
                    'y-3' => [
                        'weight' => 20,
                    ],
                    'y-4' => [
                        'weight' => 1,
                    ],
                ]
            ]],
        ];
    }

    /**
     * @param array $choiceData Données pour un choix
     * @dataProvider getOptionsDataProvider
     */
    public function testGetOptions(array $choiceData)
    {
        $this
            ->given($data = $this->newTestedInstance($choiceData))
            ->dump($data->getOptions())
            ->given($options = $data->getOptions())
            ->array($options)
                ->haskey('y-1')
                ->haskey('y-2')
                ->haskey('y-3')
                ->haskey('y-4')
            ->array($options['y-1'])
                ->hasKey('name')
                ->hasKey('weight')
                ->string['key']->isEqualTo('toto')
        ;
    }


    public function testGetRules()
    {
        $this
            ->if($choice = $this->newTestedInstance($this->getChoiceWithRules()))
            ->array($choice->getRules())
                ->hasKey('storageRule')
                ->hasSize(1)
        ;
    }
}
