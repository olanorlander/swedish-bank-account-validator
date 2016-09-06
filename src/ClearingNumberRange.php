<?php

namespace SwedishBankAccountValidator;

class ClearingNumberRange
{
    const ACCOUNT_TYPE_1_1 = '1-1';
    const ACCOUNT_TYPE_1_2 = '1-2';
    const ACCOUNT_TYPE_2_1 = '2-1';
    const ACCOUNT_TYPE_2_2 = '2-2';
    const ACCOUNT_TYPE_2_3 = '2-3';

    private $map = [];

    public static function getInstance()
    {
        return new self();
    }

    private function __construct()
    {
        // Type 1
        $this->addBankRange(Bank::AMFA_BANK_AB, 9660, 9669, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::BNP_PARIBAS_FORTIS_BANK, 9470, 9479, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::CITIBANK, 9040,9049, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::DANSKE_BANK, 1200,1399, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::DANSKE_BANK, 2400,2499, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::DNB_BANK, 9190,9199, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::DNB_BANK, 9260,9269, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::EKOBANKEN, 9700,9709, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::FOREX_BANK, 9400,9449, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::ICA_BANKEN_AB, 9270,9279, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::IKANO_BANK, 9170,9179, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::LANSFÖRSÄKRINGAR_BANK, 3400,3409, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::LANSFÖRSÄKRINGAR_BANK, 9020,9029, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::LANSFÖRSÄKRINGAR_BANK, 9060,9069, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::MARGINALEN_BANK, 9230,9239, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::NORDAX_BANK_AB, 9640,9649, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::NORDEA, 1100,1199, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::NORDEA, 1400,2099, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::NORDEA, 3000, 3299, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::NORDEA, 3301, 3399, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::NORDEA, 3410, 3781, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::NORDEA, 3783, 3999, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::NORDEA, 4000,4999, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::NORDNET_BANK, 9100,9109, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::RESURS_BANK, 9280,9289, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::RIKSGALDEN, 9880,9889, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::ROYAL_BANK_OF_SCOTLAND, 9090,9099, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::SBAB, 9250,9259, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::SEB, 5000,5999, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::SEB, 9120,9124, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::SEB, 9130,9149, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::SKANDIABANKEN, 9150,9169, self::ACCOUNT_TYPE_1_2);
        $this->addBankRange(Bank::SWEDBANK, 7000,7999, self::ACCOUNT_TYPE_1_1);
        $this->addBankRange(Bank::ALANDSBANKEN_SVERIGE_AB, 2300,2399, self::ACCOUNT_TYPE_1_2);

        // Type 2
        $this->addBankRange(Bank::DANSKE_BANK, 9180, 9189, self::ACCOUNT_TYPE_2_1);
        $this->addBankRange(Bank::HANDELSBANKEN, 6000, 6999, self::ACCOUNT_TYPE_2_2);
        $this->addBankRange(Bank::NORDEA_PLUSGIRO, 9500, 9549, self::ACCOUNT_TYPE_2_3);
        $this->addBankRange(Bank::NORDEA_PLUSGIRO, 9960, 9969, self::ACCOUNT_TYPE_2_3);
        $this->addBankRange(Bank::NORDEA_PERSON_ACCOUNT, 3300, 3300, self::ACCOUNT_TYPE_2_1);
        $this->addBankRange(Bank::NORDEA_PERSON_ACCOUNT, 3782, 3782, self::ACCOUNT_TYPE_2_1);
        $this->addBankRange(Bank::RIKSGALDEN, 9890, 9899, self::ACCOUNT_TYPE_2_1);
        $this->addBankRange(Bank::SPARBANKEN_SYD, 9570, 9579, self::ACCOUNT_TYPE_2_1);
        $this->addBankRange(Bank::SWEDBANK, 8000, 8999, self::ACCOUNT_TYPE_2_3);
        $this->addBankRange(Bank::SWEDBANK_SPARBANKEN_ORESUND, 9300, 9349, self::ACCOUNT_TYPE_2_1);
    }

    private function addBankRange($bank, $rangeStart, $rangeStop, $accountType)
    {
        $range = range($rangeStart, $rangeStop);
        $this->map = $this->map + array_fill_keys($range, ['bankName' => $bank, 'accountType' => $accountType]);
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
