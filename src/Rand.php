<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Exception\Rand\MinMustBeInferiorToMax;

/**
 * Random integer between min & max
 */
class Rand implements Randomizer
{
    public function rollForRange(int $max, int $min = 0): int
    {
        if ($max < $min) {
            throw new MinMustBeInferiorToMax($min, $max);
        }

        $range = $max - $min;
        if ($range === 0) {
            return $min;
        }

        $log = \log($range, 2);
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (int) (1 << $bits) - 1;
        do {
            $randomPseudoBytes = \openssl_random_pseudo_bytes($bytes);
            if ($randomPseudoBytes === false) {
                throw new \RuntimeException('openssl_random_pseudo_bytes failed');
            }
            $rnd = \hexdec(\bin2hex($randomPseudoBytes));
            $rnd = $rnd & $filter;
        } while ($rnd > $range);

        return (int) $min + $rnd;
    }
}
