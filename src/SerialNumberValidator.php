<?php

namespace SwedishBankAccountValidator;

class SerialNumberValidator
{
    /** @var Bank */
    private $bank;

    public function __construct(Bank $bank)
    {
        $this->bank = $bank;
    }

    /**
     * @param string $serialNumber
     * @return ValidatorResult
     */
    public function validateSerialNumber($serialNumber)
    {
        return $this->bank->validateSerialNumber($serialNumber);
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bank->getBankName();
    }
}
