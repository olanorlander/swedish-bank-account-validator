<?php

namespace SwedishBankAccountValidator;

class BankAccountValidator
{
    /**
     * @param $string
     * @return SerialNumberValidator
     */
    public static function newSerialNumberValidatorByClearingNumber($string)
    {
        $clearingNumber = new ClearingNumber($string);
        return self::requireBankWithClearingNumber($clearingNumber);
    }

    private static function requireBankWithClearingNumber(ClearingNumber $clearingNumber)
    {
        $bank = Bank::getInstanceByClearingNumber($clearingNumber);
        return new SerialNumberValidator($bank);
    }
}
