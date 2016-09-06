<?php

namespace SwedishBankAccountValidator;

use PHPUnit\Framework\TestCase;
use SwedishBankAccountValidator\Exception\InvalidSerialNumberFormatException;

class BankAccountValidatorTest extends TestCase
{
    /**
     * @param string $bankName
     * @param string $clearingNumber
     * @param string $serialNumber
     * @dataProvider validAccountNumberProvider
     */
    public function test_validating_valid_account_numbers($bankName, $clearingNumber, $serialNumber)
    {
        $result = BankAccountValidator::newSerialNumberValidatorByClearingNumber($clearingNumber)
            ->validateSerialNumber($serialNumber);

        $this->assertEquals($bankName, $result->getBankName());
        $this->assertEquals($clearingNumber, $result->getClearingNumber());
        $this->assertEquals($serialNumber, $result->getSerialNumber());
    }

    public function validAccountNumberProvider()
    {
        return [
            'Account Number Type 1-1' => ['Forex Bank', '9449', '1231230'],
            'Account Number Type 1-2' => ['Amfa Bank AB', '9661', '1231236'],
            'Account Number Type 2-1' => ['Danske Bank', '9180', '1234567897'],
            'Account Number Type 2-2' => ['Handelsbanken', '6875', '123123127'],
            'Account Number Type 2-3' => ['Nordea/Plusgirot', '9960', '1231231232'],
        ];
    }


    /**
     * @param string $clearingNumber
     * @param string $accountNumber
     * @dataProvider invalidAccountNumberProvider
     */
    public function test_that_invalid_account_numbers_throws_exception($clearingNumber, $accountNumber)
    {
        $this->expectException(InvalidSerialNumberFormatException::class);
        BankAccountValidator::newSerialNumberValidatorByClearingNumber($clearingNumber)
            ->validateSerialNumber($accountNumber);
    }

    public function invalidAccountNumberProvider()
    {
        return [
            'Account Number Type 1-1 to long' => ['9449', '1231230123'],
            'Account Number Type 1-1 to short' => ['9449', '123123'],
            'Account Number Type 1-1 letters' => ['9449', 'FABCDGHAHS'],
            'Account Number Type 2-1 to long' => ['9180', '12345678972'],
            'Account Number Type 2-2 to short' => ['6875', '2015555'],
            'Account Number Type 2-3 to long' => ['9960', '123123123123'],
        ];
    }
}
