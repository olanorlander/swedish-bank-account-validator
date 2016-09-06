<?php

namespace SwedishBankAccountValidator;

class ValidatorResult
{
    /** @var string */
    private $bankName;
    /** @var ClearingNumber */
    private $clearingNumber;
    /** @var string */
    private $accountNumber;
    /** @var bool */
    private $isValidChecksum;

    /**
     * ValidatorResult constructor.
     * @param string $bankName
     * @param ClearingNumber $clearingNumber
     * @param string $accountNumber
     * @param bool $isValidChecksum
     */
    public function __construct($bankName, ClearingNumber $clearingNumber, $accountNumber, $isValidChecksum)
    {
        $this->bankName = $bankName;
        $this->clearingNumber = $clearingNumber;
        $this->accountNumber = $accountNumber;
        $this->isValidChecksum = $isValidChecksum;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @return string
     */
    public function getClearingNumber()
    {
        return $this->clearingNumber->__toString();
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return boolean
     */
    public function isValidChecksum()
    {
        return $this->isValidChecksum;
    }
}
