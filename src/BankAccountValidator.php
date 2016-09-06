<?php

namespace SwedishBankAccountValidator;

class BankAccountValidator
{
    public function validate($clearingNumber, $accountNumber)
    {
        $bank = Bank::getInstanceByClearingNumber(new ClearingNumber($clearingNumber));
        return $bank->validateAccountNumber($accountNumber);
    }
}
