<?php

namespace Siwayll\Histoire\Modificator;

class Beauty extends Base
{

    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'beauty';
    }

    public function getInstructions()
    {
        $instructions = [
            'beautyAdd' => [$this, 'add'],
        ];
        return $instructions;
    }

    public function apply($options)
    {

        return $options;
    }

    /**
     * 
     *
     * @return self
     */
    public function add($option)
    {
        $value = (int) $option;
        $npc = $this->engine->getResult()->getStorage();
        if (isset($npc->description->charme->score)) {
            $value += $npc->description->charme->score;
        }

        $npc->set($value, 'description', 'charme', 'score');

        return null;
    }
}
