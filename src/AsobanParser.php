<?php


namespace Dnetix\Asoban;


use Dnetix\Asoban\Entities\AsobanHeader;
use Dnetix\Asoban\Entities\AsobanRecord;
use Dnetix\Asoban\Entities\AsobanResult;
use Exception;

class AsobanParser
{

    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function parse()
    {
        if (false === ($fh = fopen($this->filePath, 'rb')))
            throw new Exception(sprintf('Falló la apertura del archivo [%s].', $this->filePath), 1002);

        $result = new AsobanResult();
        
        $hasProcessedHeader = false;
        $hasProcessedBatch = false;
        $currentBatch = null;

        $rows = 0;
        while(false !== ($info = fgets($fh, 4096))) {
            $rows++;

            if (empty($info)) continue;

            if (strlen($info) != 164)
                throw new Exception(sprintf("El archivo en la línea %d no tiene la longitud esperada %d vs %d.\n%s", $rows, strlen($info), 164, $info), 1002);

            $recordType = substr($info, 0, 2);

            switch($recordType) {
                case '01':
                    if ($hasProcessedHeader)
                        throw new Exception(sprintf("El archivo en la línea %d tiene más de un registro de encabezado, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

                    $result->addHeader(new AsobanHeader([
                        'nit' => substr($info, 2, 10),
                        'date' => date('Y-m-d', strtotime(substr($info, 12, 8))),
                        'bankCode' => substr($info, 20, 3),
                        'accountNumber' => ltrim(substr($info, 23, 17), '0'),
                        'fileDate' => date('Y-m-d', strtotime(substr($info, 40, 8))),
                        'fileTime' => substr($info, 48, 4),
                        'fileModifier' => substr($info, 52, 1),
                        'accountType' => substr($info, 53, 2),
                    ]));

                    $hasProcessedHeader = true;
                    break;

                case '05':
                    if (!$hasProcessedHeader)
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de lote sin un registro previo de control, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    if ($hasProcessedBatch)
                        throw new Exception(sprintf("El archivo en la línea %d tiene más de un registro de lote anidado, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

//                    print_r(array(
//                        'serviceCode' => substr($info, 2, 13),
//                        'batchCode' => substr($info, 15, 4),
//                    ));
                    $hasProcessedBatch = true;
                    $currentBatch = substr($info, 15, 4);
                    break;

                case '06':
                    if (!$hasProcessedBatch)
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de datos sin un registro previo de lote, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

                    $data = array(
                        'reference' => ltrim(substr($info, 2, 48), '0'),
                        'amount' => floatval(ltrim(substr($info, 50, 14), '0')) / 100,
                        'origin' => substr($info, 64, 2),
                        'channel' => substr($info, 66, 2),
                        'operationId' => substr($info, 68, 6),
                        'authCode' => substr($info, 74, 6),
                        'thirdEntity' => substr($info, 80, 3),
                        'branch' => substr($info, 83, 4),
                        'sequence' => intval(ltrim(substr($info, 87, 7), '0')),
                        'refundReason' => substr($info, 94, 3),
                    );

                    $result->addRecord(new AsobanRecord($data));
                    
                    break;

                case '08':
                    if (!$hasProcessedBatch)
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote sin un registro previo de lote, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    if ($currentBatch != substr($info, 29, 4))
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote que no corresponde al lote abierto, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

//                    print_r(array(
//                        'records' => intval(ltrim(substr($info, 2, 9), '0')),
//                        'amount' => floatval(ltrim(substr($info, 11, 18), '0')) / 100,
//                        'batchCode' => substr($info, 29, 4),
//                    ));
                    $hasProcessedBatch = false;
                    $currentBatch = null;
                    break;

                case '09':
                    if (!$hasProcessedHeader)
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote sin un registro previo de control, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

//                    print_r(array(
//                        'records' => intval(ltrim(substr($info, 2, 9), '0')),
//                        'amount' => floatval(ltrim(substr($info, 11, 18), '0')) / 100,
//                    ));
                    $hasProcessedHeader = false;
                    break;
            }
        }
        fclose($fh);

        return $result;
    }

    public static function load($filePath)
    {
        return new self($filePath);
    }
    
}