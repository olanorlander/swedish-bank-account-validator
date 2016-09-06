<?php

namespace SwedishBankAccountValidator;

use SwedishBankAccountValidator\Exception\InvalidClearingNumberException;

class ClearingNumber
{
    /** @var string */
    private $clearingNumber;

    /**
     * ClearingNumber constructor.
     * @param string $clearingNumber
     */
    public function __construct($clearingNumber)
    {
        $this->clearingNumber = substr($clearingNumber, 0, 4);
        $this->guardAgainstInvalidClearingNumber();
    }

    private function guardAgainstInvalidClearingNumber()
    {
        if (!is_numeric($this->clearingNumber)) {
            throw new InvalidClearingNumberException("The clearing-number is not numeric: '$this->clearingNumber'");
        }

        if (!ClearingNumberRange::getInstance()->isSupportedClearingNumber($this->clearingNumber)) {
            throw new InvalidClearingNumberException(
                "Unsupported clearing-number: '$this->clearingNumber'"
            );
        }
    }

    public function __toString()
    {
        return $this->clearingNumber;
    }
}
