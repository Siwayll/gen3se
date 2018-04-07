<?php

namespace Gen3se\Engine\Exception;

/**
 * Exceptions with this Interface will receive the current __Option__ name's
 */
interface OptionNameInterface
{
    public function setOptionName(string $optionName): void;
}
