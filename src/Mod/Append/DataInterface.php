<?php

namespace Gen3se\Engine\Mod\Append;

use Gen3se\Engine\Choice\Option\DataInterface as OptionData;

interface DataInterface extends OptionData
{
    public function eachChoice();
}
