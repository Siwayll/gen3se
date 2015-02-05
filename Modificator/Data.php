<?php

namespace Siwayll\Histoire\Modificator;

class Data extends Base
{
    /**
     * Renvoie le nom du modificateur
     *
     * @return string
     */
    public function getName()
    {
        return 'data';
    }

    /**
     * Renvoie les instructions spécifiques au modificateur
     *
     * @return array
     */
    public function getInstructions()
    {
        $instructions = [
            'addData' => [$this, 'addData'],
            'dataConcat' => [$this, 'dataConcat'],
        ];
        return $instructions;
    }

    public function apply($options)
    {
        return $options;
    }

    public function dataConcat($options)
    {
        if (!is_array($options)) {
            $options = [$options];
        }
        $resultData = $this->engine->getCurrentResultData();
        $text = $resultData['text'];
        $current = $this->engine->getCurrent()->getName();
        foreach ($options as $choiceName) {
            $result = $this
                ->engine
                    ->setCurrent($choiceName)
                    ->getCurrent()
                    ->roll()
                    ->getResult()
            ;
            $result = $this->engine->update($result);
            $text .= $result['text'];
        }

        $this->engine->setCurrent($current);

        $finalResult = [
            'text' => $text,
        ];
        return $finalResult;
    }


    public function addData($options)
    {
        if (!is_array($options)) {
            $options = [$options];
        }
        $finalResult = $this->engine->getCurrentResultData();
        foreach ($options as $choiceName) {
            $result = $this
                ->engine
                    ->loadChoice($choiceName)
                    ->roll()
                    ->getResult()
            ;
            $finalResult = array_merge($finalResult, $result);
        }

        return $finalResult;
    }
}
