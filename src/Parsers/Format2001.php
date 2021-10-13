<?php

namespace Dnetix\Asoban\Parsers;

use Dnetix\Asoban\Entities\AsobanBatch;
use Dnetix\Asoban\Entities\AsobanControl;
use Dnetix\Asoban\Entities\AsobanEndBatch;
use Dnetix\Asoban\Entities\AsobanHeader;
use Dnetix\Asoban\Entities\AsobanRecord;

class Format2001 extends GenericParser
{
    public function lineLength()
    {
        return 162;
    }

    public function recordType($line)
    {
        return substr($line, 0, 2);
    }

    public function headerCode()
    {
        return '01';
    }

    public function batchCode()
    {
        return '05';
    }

    public function detailCode()
    {
        return '06';
    }

    public function endBatchCode()
    {
        return '08';
    }

    public function controlCode()
    {
        return '09';
    }

    /**
     * @param $row
     * @return AsobanHeader
     */
    public function parseHeader($row)
    {
        return new AsobanHeader([
            'nit' => substr($row, 2, 10),
            'date' => date('Y-m-d', strtotime(substr($row, 12, 8))),
            'bankCode' => substr($row, 20, 3),
            'accountNumber' => ltrim(substr($row, 23, 17), '0'),
            'fileDate' => date('Y-m-d', strtotime(substr($row, 40, 8))),
            'fileTime' => substr($row, 48, 4),
            'fileModifier' => substr($row, 52, 1),
            'accountType' => substr($row, 53, 2),
        ]);
    }

    /**
     * @param $row
     * @return AsobanBatch
     */
    public function parseBatch($row)
    {
        return new AsobanBatch([
            'serviceCode' => substr($row, 2, 13),
            'batchCode' => substr($row, 15, 4),
        ]);
    }

    /**
     * @param $row
     * @return AsobanRecord
     */
    public function parseDetail($row)
    {
        return new AsobanRecord([
            'reference' => ltrim(substr($row, 2, 48), '0'),
            'amount' => floatval(ltrim(substr($row, 50, 14), '0')) / 100,
            'origin' => substr($row, 64, 2),
            'channel' => substr($row, 66, 2),
            'operationId' => substr($row, 68, 6),
            'authCode' => substr($row, 74, 6),
            'thirdEntity' => substr($row, 80, 3),
            'branch' => substr($row, 83, 4),
            'sequence' => intval(ltrim(substr($row, 87, 7), '0')),
            'refundReason' => substr($row, 94, 3),
        ]);
    }

    /**
     * @param $row
     * @return AsobanEndBatch
     */
    public function parseEndBatch($row)
    {
        return new AsobanEndBatch([
            'records' => intval(ltrim(substr($row, 2, 9), '0')),
            'amount' => floatval(ltrim(substr($row, 11, 18), '0')) / 100,
            'batchCode' => substr($row, 29, 4),
        ]);
    }

    /**
     * @param $row
     * @return AsobanControl
     */
    public function parseControl($row)
    {
        return new AsobanControl([
            'records' => intval(ltrim(substr($row, 2, 9), '0')),
            'amount' => floatval(ltrim(substr($row, 11, 18), '0')) / 100,
        ]);
    }
}
