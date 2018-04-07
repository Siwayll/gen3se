<?php
/**
 * Chargement automatique des classes
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Rand extends Test
{
    public function shouldBeConstructWithVariusParametrage()
    {
        $this
            ->object($this->newTestedInstance())
            ->object($this->newTestedInstance(0))
            ->object($this->newTestedInstance(5, 15))

            ->KapowException(function () {
                $this->newTestedInstance(5, 3);
            })
                ->hasMessage('Min ({min}) must be inferior to Max ({max})')
                ->hasKapowMessage('Min (5) must be inferior to Max (3)')
                ->hasCode(Level::ERROR)

            ->KapowException(function () {
                $this->newTestedInstance(3);
            })
                ->hasKapowMessage('Min (3) must be inferior to Max (0)')
                ->hasCode(Level::ERROR)
        ;
    }

    /**
     * @return array
     */
    protected function minMaxProvider()
    {
        return [
            [0, 1],
            [0, 0],
            [-10, -1],
            [0, 400],
            [500, 500]
        ];
    }

    /**
     * @param int $min
     * @param int $max
     * @dataProvider minMaxProvider
     */
    public function shouldGetRandomIntegerBetweenMinAndMax(int $min, int $max)
    {
        $this
            ->if($this->newTestedInstance($min, $max))
            ->integer($this->testedInstance->roll())
                ->isGreaterThanOrEqualTo($min)
                ->isLessThanOrEqualTo($max)
                ->isEqualTo($this->testedInstance->getResult())
            ->dump($this->testedInstance->getResult())
        ;
    }
}
