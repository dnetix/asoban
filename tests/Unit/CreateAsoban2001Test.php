<?php

namespace Tests\Unit;

use Dnetix\Asoban\AsobanParser;
use Dnetix\Asoban\AsobanWriter;
use Dnetix\Asoban\Entities\AsobanHeader;
use Dnetix\Asoban\Entities\AsobanRecord;
use Tests\BaseTestCase;

class CreateAsoban2001Test extends BaseTestCase
{
    public function testItCreatesCorrectlyAnAsoban2001()
    {
        $file = __DIR__ . '/../files/ASOBANCREATED.txt';

        $writer = AsobanWriter::create($file);

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

        $writer->generate();

        $result = AsobanParser::load($file)->parse();

        $this->assertEquals('23241259', $result->batchs()[1]->records()[1]->reference());
        $this->assertEquals(5, $result->batchs()[1]->batchCode());
        $this->assertEquals(3, $result->batchs()[1]->records()[1]->sequence());
        $this->assertEquals(2, $result->batchs()[0]->endBatch()->records());
        $this->assertEquals(301.12, $result->batchs()[0]->endBatch()->amount());
    }
}
