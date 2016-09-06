<?php

namespace SwedishBankAccountValidator;

class ValidatorResult
{
    /** @var string */
    private $bankName;
    /** @var ClearingNumber */
    private $clearingNumber;
    /** @var string */
    private $serialNumber;

    /**
     * ValidatorResult constructor.
     * @param string $bankName
     * @param ClearingNumber $clearingNumber
     * @param string $serialNumber
     */
    public function __construct($bankName, ClearingNumber $clearingNumber, $serialNumber)
    {
        $this->bankName = $bankName;
        $this->clearingNumber = $clearingNumber;
        $this->serialNumber = $serialNumber;
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
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }
}
