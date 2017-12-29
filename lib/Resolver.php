<?php
namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Option\Option;
use Siwayll\Kapow\Exception;

class Resolver
{
    private $result;

    public function __construct(Choice $choice)
    {
        $choiceOptions = $choice->getOptionCollection();
        $rand = new Rand(0, $choiceOptions->getTotalWeight());
        $rand->roll();
        try {
            $this->result = $choiceOptions->findByPositonInStack($rand->getResult());
        } catch (Exception $exception) {
            if ($exception instanceof ChoiceNameInterface) {
                $exception->setChoiceName($choice->getName());
            }
            throw $exception;
        }
    }

    /**
     * @return Option
     */
    public function getPickedOption(): Option
    {
        return $this->result;
    }
}
