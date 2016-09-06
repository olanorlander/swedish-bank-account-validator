<?php

namespace SwedishBankAccountValidator;

use SwedishBankAccountValidator\Exception\InvalidChecksumException;
use SwedishBankAccountValidator\Exception\InvalidSerialNumberFormatException;

class Bank
{
    const AMFA_BANK_AB = 'Amfa Bank AB';
    const BNP_PARIBAS_FORTIS_BANK = 'BNP Paribas Fortis Bank';
    const CITIBANK = 'Citibank';
    const DANSKE_BANK = 'Danske Bank';
    const DNB_BANK = 'DNB Bank';
    const EKOBANKEN = 'Ekobanken';
    const FOREX_BANK = 'Forex Bank';
    const HANDELSBANKEN = 'Handelsbanken';
    const ICA_BANKEN_AB = 'ICA Banken AB';
    const IKANO_BANK = 'IKANO Bank';
    const LANSFÖRSÄKRINGAR_BANK = 'Länsförsäkringar Bank';
    const MARGINALEN_BANK = 'Marginalen Bank';
    const NORDAX_BANK_AB = 'Nordax Bank AB';
    const NORDEA = 'Nordea';
    const NORDEA_PLUSGIRO = 'Nordea/Plusgirot';
    const NORDEA_PERSON_ACCOUNT = 'Nordea - personkonto';
    const NORDNET_BANK = 'Nordnet Bank';
    const RESURS_BANK = 'Resurs Bank';
    const RIKSGALDEN = 'Riksgälden';
    const ROYAL_BANK_OF_SCOTLAND = 'Royal bank of Scotland';
    const SBAB = 'SBAB';
    const SEB = 'SEB';
    const SKANDIABANKEN = 'Skandiabanken';
    const SPARBANKEN_SYD = 'Sparbanken Syd';
    const SWEDBANK = 'Swedbank';
    const SWEDBANK_SPARBANKEN_ORESUND = 'Swedbank (f.d. Sparbanken Öresund)';
    const ALANDSBANKEN_SVERIGE_AB = 'Ålandsbanken Sverige AB';

    /** @var string */
    private $bankName;
    /** @var string */
    private $accountType;
    /** @var ClearingNumber */
    private $clearingNumber;

    /**
     * Bank constructor.
     * @param string $bankName
     * @param string $accountType
     * @param ClearingNumber $clearingNumber
     */
    private function __construct($bankName, $accountType, ClearingNumber $clearingNumber)
    {
        $this->bankName = $bankName;
        $this->accountType = $accountType;
        $this->clearingNumber = $clearingNumber;
    }

    public static function requireInstanceByClearingNumber(ClearingNumber $clearingNumber)
    {
        $range = ClearingNumberRange::getInstance()->requireBankByClearingNumber($clearingNumber);

        return new self(
            $range['bankName'],
            $range['accountType'],
            $clearingNumber
        );
    }

    /**
     * @param string $serialNumber
     * @return ValidatorResult
     */
    public function validateSerialNumber($serialNumber)
    {
        if (in_array($this->accountType, [
            ClearingNumberRange::ACCOUNT_TYPE_1_1,
            ClearingNumberRange::ACCOUNT_TYPE_1_2])
        ) {
            $this->guardAgainstInvalidType1SerialNumber($this->clearingNumber, $serialNumber);
            $checksum = $this->clearingNumber . $serialNumber;
            $checksum = $this->accountType == ClearingNumberRange::ACCOUNT_TYPE_1_1 ?
                substr($checksum, 1) : $checksum;
            $this->guardAgainstInvalidChecksum(11, $checksum);
        } elseif ($this->accountType == ClearingNumberRange::ACCOUNT_TYPE_2_1) {
            $this->guardAgainstInvalidType21SerialNumber($serialNumber);
            $this->guardAgainstInvalidChecksum(10, $serialNumber);
        } elseif ($this->accountType == ClearingNumberRange::ACCOUNT_TYPE_2_2) {
            $this->guardAgainstInvalidType22SerialNumber($serialNumber);
            $this->guardAgainstInvalidChecksum(11, $serialNumber);
        } elseif ($this->accountType == ClearingNumberRange::ACCOUNT_TYPE_2_3) {
            $this->guardAgainstInvalidType23SerialNumber($serialNumber);
            $this->guardAgainstInvalidChecksum(10, substr($serialNumber, -10));
        }

        return true;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @return ClearingNumber
     */
    public function getClearingNumber()
    {
        return $this->clearingNumber;
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

    private function guardAgainstInvalidChecksum($modulus, $number)
    {
        if ($modulus == 10 && ModulusCalculator::verifyMod10Checksum($number)) {
            return true;
        }

        if ($modulus == 11 && ModulusCalculator::verifyMod11Checksum($number)) {
            return true;
        }

        if ($this->bankName == Bank::SWEDBANK) {
            throw new InvalidChecksumException(
                "Incorrect checksum for number: $number" . PHP_EOL .
                "However, in rare cases Swedbank account number with bad checksum do exists."
            );
        }

        throw new InvalidChecksumException("Incorrect checksum for number: $number");
    }
}
