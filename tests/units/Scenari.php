<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire;

use atoum;
use Siwayll\Histoire\Loader\Simple;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Scenari extends atoum
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

    private function getChoiceTwo()
    {
        $choice = [
            'name' => 'cheveux',
            'options' => [
                [
                    'name' => 'c-1',
                    'text' => 'brun',
                    'weight' => 50,
                ],
                [
                    'name' => 'c-2',
                    'text' => 'blond',
                    'weight' => 129,
                ],
                [
                    'name' => 'c-3',
                    'text' => 'vert',
                    'weight' => 20,
                ],
                [
                    'name' => 'c-4',
                    'text' => 'chauve',
                    'weight' => 1,
                ],
            ]
        ];

        return $choice;
    }

    /**
     * Renvois un scenario de choix très simple
     *
     * @return array
     */
    private function getScenariOne()
    {
        $scenari = [
            'name'    => 'test',
            'order'   => ['yeux', 'cheveux'],
            'choices' => [$this->getChoiceOne(), $this->getChoiceTwo()],
        ];

        return new Simple($scenari);
    }

// -------------------------------------------------------------------------- //

    /**
     * Instantiation d'un scenario
     *
     * @return void
     */
    public function testConstruct()
    {
        $this
            ->exception(function () {
                $choice = new \Siwayll\Histoire\Scenari([]);
            })
                ->hasMessage('Le scenario doit être un tableau non vide.')
                ->hasCode(400)
        ;
    }

    /**
     * Contrôle du renvois du choix en cours de traitement
     *
     * @return void
     */
    public function testGetCurrent()
    {
        $this
            ->if($scenari = new \Siwayll\Histoire\Scenari($this->getScenariOne()))
            ->and($choice = new \Siwayll\Histoire\Choice($this->getChoiceOne()))
            ->object($scenari->getCurrent())
                ->isEqualTo($choice)
            ->object($scenari->getCurrent())
                ->isEqualTo($choice)
        ;
    }

    public function testSetCurrentTo()
    {
        $this
            ->if($scenari = new \Siwayll\Histoire\Scenari($this->getScenariOne()))
            ->object($scenari->setCurrentTo('yeux'))
                ->isIdenticalTo($scenari)
            ->exception(function () use ($scenari) {
                $scenari->setCurrentTo('dsfsdg');
            })
                ->hasMessage('Aucun choix n\'a le nom _dsfsdg_')
                ->hasCode(400)
        ;
    }

    /**
     * Contrôle du getter
     *
     * @return void
     */
    public function testGetChoice()
    {
        $this
            ->if($scenari = new \Siwayll\Histoire\Scenari($this->getScenariOne()))
            ->and($choice = new \Siwayll\Histoire\Choice($this->getChoiceOne()))
            ->object($scenari->getChoice('yeux'))
                ->isEqualTo($choice)
            ->exception(function () use ($scenari) {
                $scenari->getChoice('dsfsdg');
            })
                ->hasMessage('Aucun choix n\'a le nom _dsfsdg_')
                ->hasCode(400)
        ;
    }

    public function testSetChoiceResult()
    {
        $this
            ->if($scenari = new \Siwayll\Histoire\Scenari($this->getScenariOne()))
            ->object($scenari->setChoiceResult('y-2'))
                ->isIdenticalTo($scenari)
            ->if($choice = new \Siwayll\Histoire\Choice($this->getChoiceTwo()))
            ->object($scenari->getCurrent())
                ->isEqualTo($choice)
            ->exception(function () use ($scenari) {
                $scenari->setChoiceResult('y-2');
            })
                ->hasMessage('Dans _cheveux_ l\'option __y-2__ n\'existe pas')
                ->hasCode(400)
        ;
    }
}
