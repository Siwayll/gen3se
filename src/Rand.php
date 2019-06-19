<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Exception\Rand\MinMustBeInferiorToMax;

/**
 * Random integer between min & max
 */
class Rand
{
    /**
     * Result of the random generation
     */
    protected $result;

    /**
     * Minimum for the random generation
     */
    private $min = 0;

    /**
     * Maximum for the random generation
     */
    private $max = 0;


    public function __construct(int $min = 0, int $max = 0)
    {
        $this->min = $min;
        $this->max = $max;
        $this->controlRange();
    }

    /**
     * #exception
     * if _min_ > _max_
     */
    private function controlRange()
    {
        if ($this->max < $this->min) {
            throw new MinMustBeInferiorToMax($this->min, $this->max);
        }
    }

    /**
     * Generate a random integer between _min_ and _max_
     */
    public function roll(): int
    {
        $range = $this->max - $this->min;
        if ($range === 0) {
            $this->result = $this->min;
            return $this->result;
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

        $this->result = (int) $this->min + $rnd;
        return $this->result;
    }

    /**
     * Return the result of the random generation
     */
    public function getResult(): int
    {
        return $this->result;
    }
}
