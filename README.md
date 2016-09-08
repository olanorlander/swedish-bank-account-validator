# Swedish Bank Account Validator

Library for validating swedish bank accounts connected to the BankGiro-system by a combination of clearing number
and serial number.

The library validates that:
* Which bank an account number is connected to if that bank supports BankGiro
* If the serial number has the correct format and checksum (mod10 and mod11)

The logic of validating the accounts is based in the following document:

[Bankernas kontonummer](https://www.bankgirot.se/globalassets/dokument/anvandarmanualer/bankernaskontonummeruppbyggnad_anvandarmanual_sv.pdf) (Bankgirot)

![Travis](https://travis-ci.org/olanorlander/swedish-bank-account-validator.svg)

### Example
First use a static method and send in the clearing-number as a parameter to fetch a "Serial Number Validator".
```php
try {
    $serialNumberValidator = BankAccountValidator::withClearingNumber('9661');
} catch (InvalidClearingNumberException $e) {
    echo "Wrong clearing-number:" . $e->getMessage();
}
```

Then use the "Serial Number Validator" and send the serial number as parameter.
```php
try {
    $result = $serialNumberValidator->withSerialNumber('1231236');
} catch (InvalidSerialNumberFormatException $e) {
    echo 'Invalid account format for "' . $serialNumberValidator->getBankName() . '": ' . $e->getMessage();
} catch (InvalidChecksumException $e) {
    echo 'Invalid checksum for "' . $serialNumberValidator->getBankName() . '": ' . $e->getMessage();
}
```
There are some rare cases Swedbank account really exists but has a bad checksum. There a special exception
 for this.
```php
} catch (InvalidSwedbankChecksumException $e) {
    echo 'Bad checksum but possibly correct for "' . $serialNumberValidator->getBankName() . '": ' . $e->getMessage();
}
```

It's also possible to chain both methods
```php
BankAccountValidator::withClearingNumber('9661')->withSerialNumber('1231236');
```

If no exception were thrown the validator will return a ValidatorResponse indicating that the account is valid
```php
$serialNumberValidator = BankAccountValidator::withClearingNumber('9661');
$result = $serialNumberValidator->withSerialNumber('1231236');
echo 'Valid account' . PHP_EOL;
echo 'Bank: ' . $result->getBankName() . PHP_EOL;
echo 'Clearingnr: ' . $result->getClearingNumber() . PHP_EOL;
echo 'Serialnr: ' . $result->getSerialNumber() . PHP_EOL;

```

## System requirements
- **PHP v >= 5.5.0**

## License

[MIT license](http://opensource.org/licenses/MIT)