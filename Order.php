<?php

namespace Siwayll\Histoire;

/**
 *
 */
class Order
{
    private $list = [];
    private $current = null;

    public function hasModificators()
    {
        return true;
    }

    public function getInstructions()
    {
        return [
            'addAtEnd'   => [$this, 'addAtEnd'],
            'addNext'    => [$this, 'addFurther'],
        ];
    }

    public function hasNext()
    {
        return !empty($this->list);
    }

    /**
     * Send next order
     *
     * @return string
     */
    public function getNext()
    {
        $this->current = array_shift($this->list);
        return $this->current;
    }

    /**
     * Add an order after the current
     *
     * @param string $name Name of the order
     */
    public function addFurther($name)
    {
        if (!is_array($name)) {
            $name = [$name];
        }
        array_unshift($this->list, ...$name);
        return $this;
    }

    /**
     * Add at end of the order list
     *
     * @var string $name Name of the order
     *
     * @return self
     */
    public function addAtEnd($name)
    {
        if (!is_array($name)) {
            $name = [$name];
        }
        array_push($this->list, ...$name);
        return $this;
    }
}
