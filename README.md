# Swedish Bank Account Validator

Library for validating swedish bank accounts connected to the BankGiro-system by a combination of clearing number
and serial number.

The library validates that:
* Which bank an account number is connected to if that bank supports BankGiro
* If the serial number has the correct format and checksum

The logic of validating the accounts is based in the following document:

[Bankernas kontonummer](https://www.bankgirot.se/globalassets/dokument/anvandarmanualer/bankernaskontonummeruppbyggnad_anvandarmanual_sv.pdf) (Bankgirot)

![Travis](https://travis-ci.org/olanorlander/swedish-bank-account-validator.svg)

### Example
First use a static method with the clearing-number to fetch a "Serial Number Validator".
```php
try {
    $serialNumberValidator = BankAccountValidator::newSerialNumberValidatorByClearingNumber('9661');
} catch (InvalidClearingNumberException $e) {
    echo "Wrong clearing-number:" . $e->getMessage();
}
```

Then use the "Serial Number Validator".
```php
try {
    $serialNumberValidator->validateSerialNumber('1231236')
} catch (InvalidSerialNumberFormatException $e) {
    echo 'Invalid account format for "' . $serialNumberValidator->getBankName() . '": ' . $e->getMessage();
} catch (InvalidChecksumException $e) {
    echo 'Invalid checksum for "' . $serialNumberValidator->getBankName() . '": ' . $e->getMessage();
}
```

It's also possible to chain both methods
```php
BankAccountValidator::newSerialNumberValidatorByClearingNumber('9661')->validateSerialNumber('1231236');
```

