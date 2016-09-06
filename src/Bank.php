<?php

namespace SwedishBankAccountValidator;

use SwedishBankAccountValidator\Exception\InvalidSerialNumberFormatException;

class Bank
{
    const AMFA_BANK_AB = 'Amfa Bank AB';
    const AVANZA_BANK_AB = 'Avanza Bank AB';
    const DANSKE_BANK = 'Danske Bank';
    const FOREX_BANK = 'Forex Bank';
    const HANDELSBANKEN = 'Handelsbanken';
    const NORDEA_PLUSGIRO = 'Nordea/Plusgirot';

    const ACCOUNT_NUMBER_TYPE_1_1 = '1-1';
    const ACCOUNT_NUMBER_TYPE_1_2 = '1-2';
    const ACCOUNT_NUMBER_TYPE_2_1 = '2-1';
    const ACCOUNT_NUMBER_TYPE_2_2 = '2-2';
    const ACCOUNT_NUMBER_TYPE_2_3 = '2-3';

    private static $accountTypes = [
        self::AMFA_BANK_AB => self::ACCOUNT_NUMBER_TYPE_1_2,
        self::FOREX_BANK => self::ACCOUNT_NUMBER_TYPE_1_1,
        self::DANSKE_BANK => self::ACCOUNT_NUMBER_TYPE_2_1,
        self::HANDELSBANKEN => self::ACCOUNT_NUMBER_TYPE_2_2,
        self::NORDEA_PLUSGIRO => self::ACCOUNT_NUMBER_TYPE_2_3
    ];

    /** @var string */

    private $bankName;
    /** @var ClearingNumber */
    private $clearingNumber;

    /**
     * Bank constructor.
     * @param string $bankName
     * @param ClearingNumber $clearingNumber
     */
    private function __construct($bankName, ClearingNumber $clearingNumber)
    {
        $this->bankName = $bankName;
        $this->clearingNumber = $clearingNumber;
    }

    public static function getInstanceByClearingNumber(ClearingNumber $clearingNumber)
    {
        return new self(
            ClearingNumberRange::getInstance()->requireBankByClearingNumber($clearingNumber),
            $clearingNumber
        );
    }

    /**
     * @param string $serialNumber
     * @return ValidatorResult
     */
    public function validateSerialNumber($serialNumber)
    {
        if (in_array($this->getAccountNumberType(), [self::ACCOUNT_NUMBER_TYPE_1_1, self::ACCOUNT_NUMBER_TYPE_1_2])) {
            $this->guardAgainstInvalidType1SerialNumber($this->clearingNumber, $serialNumber);
            $checksum = $this->clearingNumber . $serialNumber;
            $checksum = $this->getAccountNumberType() == self::ACCOUNT_NUMBER_TYPE_1_1 ?
                substr($checksum, 1) : $checksum;
            return $this->response($serialNumber, $this->verifyMod11Checksum($checksum));
        } elseif ($this->getAccountNumberType() == self::ACCOUNT_NUMBER_TYPE_2_1) {
            $this->guardAgainstInvalidType21SerialNumber($serialNumber);
            return $this->response($serialNumber, $this->verifyMod10Checksum($serialNumber));
        } elseif ($this->getAccountNumberType() == self::ACCOUNT_NUMBER_TYPE_2_2) {
            $this->guardAgainstInvalidType22SerialNumber($serialNumber);
            return $this->response($serialNumber, $this->verifyMod11Checksum($serialNumber));
        } elseif ($this->getAccountNumberType() == self::ACCOUNT_NUMBER_TYPE_2_3) {
            $this->guardAgainstInvalidType23SerialNumber($serialNumber);
            return $this->response($serialNumber, $this->verifyMod10Checksum(substr($serialNumber, -10)));
        }
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    private function response($serialNumber, $isValidChecksum)
    {
        return new ValidatorResult(
            $this->bankName,
            $this->clearingNumber,
            $serialNumber,
            $isValidChecksum
        );
    }

    private function getAccountNumberType()
    {
        return self::$accountTypes[$this->bankName];
    }

    private function guardAgainstInvalidType1SerialNumber(ClearingNumber $clearingNumber, $serialNumber)
    {
        $merged = $clearingNumber . $serialNumber;
        if (!preg_match('/^\d{11}$/', $merged)) {
            throw new InvalidSerialNumberFormatException(
                "Clearing-number and serial-number should be exactly 11 digits: '$merged'"
            );
        }
    }

    private function guardAgainstInvalidType21SerialNumber($serialNumber)
    {
        if (!preg_match('/^\d{10}$/', $serialNumber)) {
            throw new InvalidSerialNumberFormatException(
                "Serial-number should be exactly 10 digits: '$serialNumber'"
            );
        }
    }

    private function guardAgainstInvalidType22SerialNumber($serialNumber)
    {
        if (!preg_match('/^\d{9}$/', $serialNumber)) {
            throw new InvalidSerialNumberFormatException(
                "Serial-number should be exactly 9 digits: '$serialNumber'"
            );
        }
    }

    private function guardAgainstInvalidType23SerialNumber($serialNumber)
    {
        if (!preg_match('/^\d{1,10}$/', $serialNumber)) {
            throw new InvalidSerialNumberFormatException(
                "Serial-number should be maximum 10 digits: '$serialNumber'"
            );
        }
    }

    /**
     * @param string $number
     * @return bool
     */
    private function verifyMod11Checksum($number)
    {
        $numberLength = strlen($number);
        $sum = 0;
        $weights = [1, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
        $arr = array_splice(
            $weights,
            count($weights) - $numberLength,
            count($weights) - (count($weights) - $numberLength)
        );

        while ($numberLength) {
          $value = intval(substr($number,--$numberLength, 1), 10);
          $x = $arr[$numberLength] * $value;
          $sum += $x;
        }

        return $sum && $sum % 11 === 0;
    }


    /**
     * @param string $number
     * @return bool
     */
    private function verifyMod10Checksum($number)
    {
        $numberLength = strlen($number);
        $bit = 1;
        $sum = 0;
        $arr = [0, 2, 4, 6, 8, 1, 3, 5, 7, 9];

        while ($numberLength) {
            $value = intval(substr($number, --$numberLength, 1), 10);
            $sum += ($bit ^= 1) ? $arr[$value] : $value;
        }

        return $sum && $sum % 10 === 0;
    }
}
