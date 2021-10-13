# Asobancaria Parser

[![codecov](https://codecov.io/gh/dnetix/asoban/branch/master/graph/badge.svg?token=738P5X6J4H)](https://codecov.io/gh/dnetix/asoban)

Reads asobancaria plain text files and parse them into AsobanResults.

## Examples

### Reading

- Parsing Asobancaria 2001
```
$result = \Dnetix\Asoban\AsobanParser::load(FILE_PATH)->parse();
```

- Parsing Asobancaria 1998
```
$result = \Dnetix\Asoban\AsobanParser::load(FILE_PATH, \Dnetix\Asoban\AsobanParser::F_1998)->parse();
```

At the moment parses Asobancaria 2001 and 1988 formats

### Wrtiting

```php
$writer = AsobanWriter::create('YOUR_FILE_PATH');

$writer->addHeader([
    'nit' => '9001238182',
    'date' => date('Y-m-d'),
    'bankCode' => 8,
    'accountNumber' => '00849514000',
    'accountType' => AsobanHeader::AC_SAVINGS,
]);

$writer->addBatch([
    'serviceCode' => '1234567890123',
    'batchCode' => 4,
])->addRow([
    'reference' => '123123123',
    'amount' => 100.12,
    'origin' => AsobanRecord::SOURCE_CREDIBANCO,
    'channel' => AsobanRecord::PM_CARD_INTERNET,
    'operationId' => '012345',
    'authCode' => '000000',
    'branch' => '1234567',
])->addRow([
    'reference' => '213124124',
    'amount' => 201,
    'origin' => AsobanRecord::SOURCE_RBM,
    'channel' => AsobanRecord::PM_CARD_INTERNET,
    'operationId' => '12345',
    'authCode' => '090001',
    'branch' => '1234567',
]);

$writer->addBatch([
    'serviceCode' => '123123999',
])->addRow([
    'reference' => '2341234',
    'amount' => 92780,
    'origin' => AsobanRecord::SOURCE_SAVINGS,
    'channel' => AsobanRecord::PM_ATM,
    'operationId' => '012345',
    'authCode' => '000001',
    'branch' => '1234567',
])->addRow([
    'reference' => '23241259',
    'amount' => 821400,
    'origin' => AsobanRecord::SOURCE_BANK,
    'channel' => AsobanRecord::PM_AUTOSERVICE,
    'operationId' => '12345',
    'authCode' => '090002',
    'branch' => '1234567',
]);

// This will write the file with the information
$writer->generate();
```

## TODO

* Better handling of the batchs for the 2001 format