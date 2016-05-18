<?php

namespace Siwayll\Gen3se\Modificator;

use \Siwayll\Gen3se\Register;
use \Siwayll\Gen3se\Error\Level;
use \Exception;

/**
 * Ajout de la notion d'experience
 *
 * @author  Siwaÿll <sanath.labs@gmail.com>
 * @license MIT http://mit-license.org/
 */
class Beauty extends Base
{
    static protected $level = [
        'Ignoble' => null,
        '{Dégoûtant@HOMME|Dégoûtante@FEMME}' => -10000,
        '{Affreux@HOMME|Affreuse@FEMME}' => -5000,
        '{Laid@HOMME|Laide@FEMME}' => -3000,
        'Pas très {beaux@HOMME|belle@FEMME}' => -1000,
        'Passable' => 0,
        '{Moyen@HOMME|Moyenne@FEMME}' => 1000,
        '{Mignon@HOMME|Mignonne@FEMME}' => 3000,
        '{Beau@HOMME|belle@FEMME}' => 5000,
        'Très {Beau@HOMME|belle@FEMME}' => 10000,
        'Très grand beauté' => 20000,
        'Beauté incomparable' => 40000,
        'Beauté divine' => 80000,
    ];

    protected $value = 0;

    protected $history = [];

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'beauty';
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [
            'beautyAdd' => [$this, 'add'],
        ];
        return $instructions;
    }

    public function getDatas()
    {
        return [
            'value' => $this->value,
            'history' => $this->history,
        ];
    }

    /**
     * Non utilisé
     *
     * @param array $options Paramétrage du choix
     *
     * @return array
     */
    public function apply($options)
    {
        return $options;
    }

    /**
     * Récupère les informations de la ligne d'option
     *
     * @Param string $option Ligne d'option
     *
     * @param $option
     * @return int Valeur de variation
     * @throws Exception
     */
    protected function parseOption($option)
    {
        $pattern = '/(?<word>[a-z:]+) ((?<sign>\+|-|~)(?<var>[0-9]+))/';
        if (!preg_match($pattern, $option, $match)) {
            throw new Exception(
                '__beauty__ _' . $option . '_ mal formaté',
                Level::NOTICE
            );
        }

        // On applique la valeur négative si nécessaire
        if ($match['sign'] == '-') {
            $match['var'] = '-' . $match['var'];
        }

        return [
            'value' => (int) $match['var'],
            'sign' => $match['sign'],
        ];
    }

    /**
     * Lissage de la beauté
     *
     * @param int $div Valeur de division
     *
     * @return int Nouveau score de beauté
     */
    protected function smooth($div)
    {
        if ($this->value > 1000) {
            $value = (($this->value - 1000) / $div) + 1000;
        } else {
            $value = (($this->value + 1000) / $div) - 1000;
        }

        return $value;

    }

    /**
     * Evolution du score de beautée
     *
     * @param int $option Modificateur du score de beauté
     *
     * @return self
     */
    public function add($option)
    {
        $this->history[] = $option;

        $data = $this->parseOption($option);
        switch ($data['sign']) {
            case '~':
                $this->value = $this->smooth($data['value']);
                break;

            default:
                $this->value += $data['value'];
        }

        return null;
    }

    /**
     * Renvoi la chaîne associé au score de beauté.
     * La chaîne est compatible avec le mod Gender
     *
     * @return string
     */
    public function getLitteral()
    {
        $str = '';
        foreach (self::$level as $text => $value) {
            if ((int) $this->value >= $value) {
                $str = $text;
            }
        }

        return $str;
    }
}
