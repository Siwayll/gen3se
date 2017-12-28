<?php
namespace Gen3se\Engine;

use \Exception;
use Gen3se\Engine\Exception\Rand\MinMustBeInferiorToMax;

/**
 * Class Rand
 * @package Gen3se\Engine
 */
class Rand
{
    /**
     * @var int
     */
    protected $result;

    /**
     * @var int
     */
    private $min = 0;

    /**
     * @var int
     */
    private $max = 0;


    /**
     * Rand constructor.
     * @param int $min
     * @param int $max
     * @throws Exception
     */
    public function __construct(int $min = 0, int $max = 0)
    {
        $this->min = $min;
        $this->max = $max;
        $this->controlRange();
    }

    /**
     * @throws MinMustBeInferiorToMax
     */
    private function controlRange()
    {
        if ($this->max < $this->min) {
            throw new MinMustBeInferiorToMax($this->min, $this->max);
        }
    }

    /**
     * @return int
     */
    public function roll(): int
    {
        $range = $this->max - $this->min;
        if ($range == 0) {
            $this->result = $this->min;
            return $this->result;
        }

        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (int) (1 << $bits) - 1;
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter;
        } while ($rnd > $range);

        $this->result = (int) $this->min + $rnd;
        return $this->result;
    }

    /**
     * @return int
     */
    public function getResult(): int
    {
        return $this->result;
    }
}
