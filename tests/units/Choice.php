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
class Choice extends atoum
{
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
                $choice = new \Siwayll\Histoire\Choice($data);
            })
                ->hasMessage('Utilisation d\'un choix sans nom impossible.')
                ->hasCode(400)
            ->exception(function () {
                $choice = new \Siwayll\Histoire\Choice([]);
            })
                ->hasMessage('L\'architecture du choix doit être un tableau non vide.')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                $data['name'] = '';
                $choice = new \Siwayll\Histoire\Choice($data);
            })
                ->hasMessage('Utilisation d\'un choix sans nom impossible.')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                unset($data['options']);
                $choice = new \Siwayll\Histoire\Choice($data);
            })
                ->hasMessage('Le choix _yeux_ doit avoir des options.')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                unset($data['options'][0]['name']);
                $choice = new \Siwayll\Histoire\Choice($data);
            })
                ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                $data['options'][0]['name'] = '';
                $choice = new \Siwayll\Histoire\Choice($data);
            })
                ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                ->hasCode(400)
            ->exception(function () {
                $data = $this->getChoiceOne();
                $data['options'][0]['name'] = null;
                $choice = new \Siwayll\Histoire\Choice($data);
            })
                ->hasMessage('Dans _yeux_ l\'option __0__ n\'a pas de nom')
                ->hasCode(400)
            ->if ($data = $this->getChoiceOne())
            ->and($data['options'][0]['name'] = '0')
            ->object(new \Siwayll\Histoire\Choice($data))
            ->exception(function () {
                $data = $this->getChoiceOne();
                unset($data['options'][1]['weight']);
                $choice = new \Siwayll\Histoire\Choice($data);
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
            ->if($choice = new \Siwayll\Histoire\Choice($this->getChoiceOne()))
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
            ->if($choice = new \Siwayll\Histoire\Choice($this->getChoiceOne()))
            ->string($choice->getName())
                ->isEqualTo('yeux')
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
            ->if($choice = new \Siwayll\Histoire\Choice($this->getChoiceOne()))
            ->variable($choice->getResult())
                ->isNull()
            ->object($choice->roll())
                ->isIdenticalTo($choice)
            ->array($choice->getResult())
                ->hasKey('text')
                ->hasKey('weight')
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
            ->if($choice = new \Siwayll\Histoire\Choice($this->getChoiceOne()))
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

    public function testGetPercent()
    {
        $this
            ->if($choice = new \Siwayll\Histoire\Choice($this->getChoiceOne()))
            ->array($choice->getPercent())
                ->isEqualTo(['y-1' => 25, 'y-2' => 64.5 , 'y-3' => 10, 'y-4' => 0.5 ])
        ;
    }
}
