<?php

namespace SwedishBankAccountValidator;

class ModulusCalculator
{
    /**
     * @param string $number
     * @return bool
     */
    public static function verifyMod11Checksum($number)
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
            $value = intval(substr($number, --$numberLength, 1), 10);
            $x = $arr[$numberLength] * $value;
            $sum += $x;
        }

        return $sum && $sum % 11 === 0;
    }


    /**
     * @param string $number
     * @return bool
     */
    public static function verifyMod10Checksum($number)
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
