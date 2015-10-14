<?php
/**
 * Created by PhpStorm.
 * User: siwayll
 * Date: 10/10/15
 * Time: 09:19
 */

namespace Siwayll\Histoire;


use Siwayll\Histoire\Constraint\Rule;

class Constraint
{
    private $rules = [];

    /**
     * @param $choiceName
     * @param Rule $rule
     * @return $this
     */
    public function setRuleTo($choiceName, Rule $rule)
    {
        $this->rules[$choiceName] = $rule;

        return $this;
    }

    /**
     * @param Choice $choice
     * @return bool
     */
    public function hasRuleFor(Choice $choice)
    {
        if (isset($this->rules[$choice->getName()])) {
            return true;
        }

        return false;
    }

    /**
     * @param Choice $choice
     * @return Rule
     */
    public function getRulesFor(Choice $choice)
    {
        return $this->rules[$choice->getName()];
    }

    /**
     * @param Choice $choice
     * @return $this
     */
    public function markAsTreated(Choice $choice)
    {
        unset($this->rules[$choice->getName()]);
        return $this;
    }
}