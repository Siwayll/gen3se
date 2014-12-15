<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
namespace Siwayll\Histoire;

use \Exception;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Rand
{
    /**
     * Résultat du dernier "jet" aléatoire
     *
     * @var int
     */
    protected $result;

    private $min = 0;
    private $max = 0;


    /**
     *
     *
     * @param int $min Plus petite valeur à retourner
     * @param int $max Plus grande valeur à retourner
     */
    public function __construct($min = 0, $max = 0)
    {
        $this
            ->setMax($max)
            ->setMin($min)
            ->controlRange()
        ;
    }

    /**
     * Test si une valeur est un entier
     *
     * @param mixed  $value Valeur à tester
     * @param string $name  Nom du test (pour ientification)
     *
     * @return self
     * @throws Exception si la valeur n'est pas un entier
     */
    private function isInteger($value, $name)
    {
        if ($value != (int) $value) {
            throw new Exception($name . ' doit être un entier', 400);
        }

        return $this;
    }

    /**
     *
     * @return self
     * @throws Exception si l'écart entre min et max n'est pas correcte
     */
    private function controlRange()
    {
        if ($this->max < $this->min) {
            throw new Exception('Max doit être supérieur à min', 400);
        }

        return $this;
    }

    /**
     *
     * @param int $min Plus petite valeur à retourner
     * @return self
     */
    public function setMin($min)
    {
        $this->isInteger($min, 'Min');

        $this->min = (int) $min;
        return $this;
    }

    /**
     *
     * @param int $max Plus grande valeur à retourner
     * @return self
     */
    public function setMax($max)
    {
        $this->isInteger($max, 'Max');

        $this->max = (int) $max;
        return $this;
    }

    /**
     *
     * @return int
     */
    public function roll()
    {
        $this->controlRange();

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
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes, $s)));
            $rnd = $rnd & $filter;
        } while ($rnd > $range);

        $this->result = (int) $this->min + $rnd;
        return $this->result;
    }

    /**
     *
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }
}