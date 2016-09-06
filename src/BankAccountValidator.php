<?php

namespace SwedishBankAccountValidator;

class BankAccountValidator
{
    /**
     * @param $string
     * @return SerialNumberValidator
     */
    public static function withClearingNumber($string)
    {
        $clearingNumber = new ClearingNumber($string);
        $bank = Bank::requireInstanceByClearingNumber($clearingNumber);
        return new SerialNumberValidator($bank);
    }
}
