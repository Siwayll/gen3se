<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire;

use atoum;
use \Siwayll\Histoire\Choice as TestedClass;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Choice extends atoum
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
     * Renvoie un choix avec un paramétrage TAGS
     *
     * @return array
     */
    private function getChoiceWithTags()
    {
        $choice = [
            'name' => 'yeux',
            'options' => [
                [
                    'name' => 'y-1',
                    'text' => 'bleu',
                    'weight' => 50,
                    'tags' => [
                        'TAG1' => 2,
                    ],
                ],
                [
                    'name' => 'y-2',
                    'text' => 'marron',
                    'weight' => 129,
                    'tags' => [
                        'TAG2' => 0.2,
                    ],
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
                    'tags' => [
                        'TAG2' => 75,
                    ],
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
            ->exception(function () {
                $data = $this->getChoiceOne();
                unset($data['name']);
                $choice = new TestedClass($data);
            })
                ->hasMessage('Utilisation d\'un choix sans nom impossible.')
                ->hasCode(400)
            ->exception(function () {
                $choice = new TestedClass([]);
            })
                ->hasMessage('L\'architecture du choix doit être un tableau non vide.')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                $data['name'] = '';
                $choice = new TestedClass($data);
            })
                ->hasMessage('Utilisation d\'un choix sans nom impossible.')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                unset($data['options']);
                $choice = new TestedClass($data);
            })
                ->hasMessage('Le choix _yeux_ doit avoir des options.')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                unset($data['options'][0]['name']);
                $choice = new TestedClass($data);
            })
                ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                $data['options'][0]['name'] = '';
                $choice = new TestedClass($data);
            })
                ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                $data['options'][0]['name'] = null;
                $choice = new TestedClass($data);
            })
                ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                ->hasCode(400)
            ->if ($data = $this->getChoiceOne())
            ->and($data['options'][0]['name'] = '0')
            ->object(new TestedClass($data))
            ->exception(function () {
                $data = $this->getChoiceOne();
                unset($data['options'][1]['weight']);
                $choice = new TestedClass($data);
            })
                ->hasMessage('Dans _yeux_ __weight__ est manquant pour _y-2_')
                ->hasCode(400)

        ;
    }

    /**
     * Contrôle du getter
     *
     * @return void
     */
    public function testGetOption()
    {
        $this
            ->if($choice = new TestedClass($this->getChoiceOne()))
            ->array($choice->getOption('y-1'))
                ->isEqualTo(['name' => 'y-1', 'text' => 'bleu', 'weight' => 50])
            ->exception(function () use ($choice) {
                $choice->getOption('skjdgh');
            })
                ->hasMessage('Dans _yeux_ l\'option __skjdgh__ n\'existe pas')
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
            ->if($choice = new TestedClass($this->getChoiceOne()))
            ->string($choice->getName())
                ->isEqualTo('yeux')
        ;
    }


    /**
     * Contrôle de l'édition à la volée du choix
     *
     * @return void
     */
    public function testUpdate()
    {
        $this
            ->if($choice = new TestedClass($this->getChoiceOne()))
            ->exception(function () use ($choice) {
                $choice->update('sdfgh', ['$inc' => 50]);
            })
                ->hasMessage('Dans _yeux_ l\'option __sdfgh__ n\'existe pas')
                ->hasCode(400)
            ->object($choice->update('y-1', ['_inc' => ['weight' => 8]]))
                ->isIdenticalTo($choice)
            ->array($choice->getOption('y-1'))
                ->isEqualTo(['name' => 'y-1', 'text' => 'bleu', 'weight' => 58])
            ->object($choice->update('y-2', ['_add' => ['infoSupp' => 'toto']]))
                ->isIdenticalTo($choice)
            ->array($choice->getOption('y-2'))
                ->isEqualTo(['name' => 'y-2', 'text' => 'marron', 'weight' => 129, 'infoSupp' => 'toto'])
        ;
    }

    /**
     * Contrôle résultat du choix
     *
     * @return void
     */
    public function testRoll()
    {
        $this
            ->if($choice = new TestedClass($this->getChoiceOne()))
            ->variable($choice->getResult())
                ->isNull()
            ->object($choice->roll())
                ->isIdenticalTo($choice)
            ->object($choice->roll())
                ->isIdenticalTo($choice)
            ->array($choice->getResult())
                ->hasKey('text')
                ->hasKey('weight')
            ->if($choice->update('y-1', ['_set' => ['weight' => 0]]))
            ->and($choice->update('y-2', ['_set' => ['weight' => 0]]))
            ->and($choice->update('y-3', ['_set' => ['weight' => 0]]))
            ->and($choice->update('y-4', ['_set' => ['weight' => 0]]))
            ->exception(function () use ($choice) {
                $choice->roll();
            })
                ->hasMessage('Aucun choix possible')
                ->hasCode(400)
        ;
    }

    public function testGetPercent()
    {
        $this
            ->if($choice = new TestedClass($this->getChoiceOne()))
            ->array($choice->getPercent())
                ->isEqualTo(['y-1' => 25, 'y-2' => 64.5 , 'y-3' => 10, 'y-4' => 0.5 ])
        ;
    }

    /**
     * Contrôle de la gestion des tags
     *
     * @return void
     */
    public function testTags()
    {
        $this
            ->if($choice = new TestedClass($this->getChoiceWithTags()))
            ->object($choice->addTags(['TAG1' => true]))
                ->isIdenticalTo($choice)
            ->array($choice->getPercent())
                ->isEqualTo(['y-1' => 40, 'y-2' => 51.6 , 'y-3' => 8, 'y-4' => 0.4 ])
            ->object($choice->addTags(['TAG2' => true]))
                ->isIdenticalTo($choice)
            ->array($choice->getPercent())
                ->isEqualTo(['y-1' => 40, 'y-2' => 51.6 , 'y-3' => 8, 'y-4' => 0.4 ])
            ->object($choice->resetCaches())
                ->isIdenticalTo($choice)
            ->array($choice->getPercent())
                ->isEqualTo(['y-1' => 29.24, 'y-2' => 15.205, 'y-3' => 11.696, 'y-4' => 43.86])
        ;
    }
}
