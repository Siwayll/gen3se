<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Randomizer;
use Gen3se\Engine\Specs\Units\Core\Test;
use Siwayll\Kapow\Level;

class Rand extends Test
{
    public function shouldBeARandomizer(): void
    {
        $this
            ->testedClass
                ->hasInterface(Randomizer::class)
        ;
    }

    public function shouldThrowAnExceptionWhenMinIsGreterThanMax(): void
    {
        $this
            ->given($this->newTestedInstance())
            ->KapowException(function () {
                $this->testedInstance->rollForRange(3, 5);
            })
                ->hasMessage('Min ({min}) must be inferior to Max ({max})')
                ->hasKapowMessage('Min (5) must be inferior to Max (3)')
                ->hasCode(Level::ERROR)
        ;
    }

    public function shouldAcceptVariousParameters(): void
    {
        $this
            ->if($this->newTestedInstance())
            ->integer($this->testedInstance->rollforrange(10))
                ->isGreaterThanOrEqualTo(0)
                ->isLessThanOrEqualTo(10)
        ;
    }

    protected function minMaxProvider()
    {
        return [
            [0, 1],
            [0, 0],
            [-10, -1],
            [0, 400],
            [500, 500],
        ];
    }

    /** @dataProvider minMaxProvider */
    public function shouldGetRandomIntegerBetweenMinAndMax(int $min, int $max)
    {
        $this
            ->if($this->newTestedInstance())
            ->integer($this->testedInstance->rollForRange($max, $min))
                ->isGreaterThanOrEqualTo($min)
                ->isLessThanOrEqualTo($max)
        ;
    }
}
