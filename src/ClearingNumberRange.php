<?php

namespace SwedishBankAccountValidator;

class ClearingNumberRange
{
    private $map = [];

    public static function getInstance()
    {
        return new self();
    }

    private function __construct()
    {
        $this->addBankRange(Bank::AMFA_BANK_AB, 9660, 9669);
        $this->addBankRange(Bank::AVANZA_BANK_AB, 9550, 9569);
        $this->addBankRange(Bank::DANSKE_BANK, 9180, 9189);
        $this->addBankRange(Bank::FOREX_BANK, 9400, 9449);
        $this->addBankRange(Bank::HANDELSBANKEN, 6000, 6999);
        $this->addBankRange(Bank::NORDEA_PLUSGIRO, 9500, 9549);
        $this->addBankRange(Bank::NORDEA_PLUSGIRO, 9960, 9969);
    }

    private function addBankRange($bank, $rangeStart, $rangeStop)
    {
        $range = range($rangeStart, $rangeStop);
        $this->map = $this->map + array_fill_keys($range, $bank);
    }

    public function requireBankByClearingNumber(ClearingNumber $clearingNumber)
    {
        return $this->map[$clearingNumber->__toString()];
    }

    /**
     * @param string $clearingNumberStr
     * @return bool
     */
    public function isSupportedClearingNumber($clearingNumberStr)
    {
        return array_key_exists($clearingNumberStr, $this->map);
    }
}
