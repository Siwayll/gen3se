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
                ->hasMessage('Aucune option n\'a le nom _skjdgh_')
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
                ->hasMessage('Aucune option n\'a le nom _sdfgh_')
                ->hasCode(400)
            ->object($choice->update('y-1', ['$inc' => 8]))
                ->isIdenticalTo($choice)
            ->array($choice->getOption('y-1'))
                ->isEqualTo(['name' => 'y-1', 'text' => 'bleu', 'weight' => 58])
        ;
    }
}
