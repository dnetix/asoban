<?php


namespace Dnetix\Asoban\Parsers;
use Dnetix\Asoban\Entities\AsobanBatch;
use Dnetix\Asoban\Entities\AsobanControl;
use Dnetix\Asoban\Entities\AsobanEndBatch;
use Dnetix\Asoban\Entities\AsobanHeader;
use Dnetix\Asoban\Entities\AsobanRecord;
use Dnetix\Asoban\Entities\AsobanResult;
use Exception;

/**
 * Class GenericParser
 * Handles the process
 */
abstract class GenericParser
{
    
    protected $fileDescriptor;
    protected $filePath;
    
    private $hasProcessedHeader = false;
    private $hasProcessedBatch = false;
    private $currentBatch = null;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    private function filePath()
    {
        return $this->filePath;
    }

    private function getFileDescriptor()
    {
        $filePath = $this->filePath();
        
        if (!file_exists($this->filePath())){
            throw new Exception('File not exists to open the file [' . $filePath . ']');
        }
        
        $this->fileDescriptor = fopen($filePath, 'rb');
        
        if ($this->fileDescriptor === false){
            throw new Exception('Failed to open the file [' . $filePath . ']');
        }
        
        return $this->fileDescriptor;
    }

    private function closeFile()
    {
        fclose($this->fileDescriptor);
    }

    private function getNextLine()
    {
        return fgets($this->fileDescriptor, 4096);
    }
    
    public abstract function lineLength();
    public abstract function recordType($line);
    public abstract function headerCode();
    public abstract function batchCode();
    public abstract function detailCode();
    public abstract function endBatchCode();
    public abstract function controlCode();

    /**
     * @param $row
     * @return AsobanHeader
     */
    public abstract function parseHeader($row);

    /**
     * @param $row
     * @return AsobanBatch
     */
    public abstract function parseBatch($row);

    /**
     * @param $row
     * @return AsobanRecord
     */
    public abstract function parseDetail($row);

    /**
     * @param $row
     * @return AsobanEndBatch
     */
    public abstract function parseEndBatch($row);

    /**
     * @param $row
     * @return AsobanControl
     */
    public abstract function parseControl($row);
    

    /**
     * @return AsobanResult
     * @throws Exception
     */
    public function parse()
    {
        $this->getFileDescriptor();

        $result = new AsobanResult();

        $rows = 0;

        while(false !== ($info = $this->getNextLine())) {
            $rows++;

            if (empty($info)) continue;

            if (strlen($info) != $this->lineLength())
                throw new Exception('Line ' . $rows . ' has invalid length. ' . strlen($info) . ' expected: ' . $this->lineLength());

            $recordType = $this->recordType($info);

            switch($recordType) {
                case $this->headerCode():
                    if ($this->hasProcessedHeader)
                        throw new Exception('The file [' . $this->filePath() . '] has more than one header');

                    $result->addHeader($this->parseHeader($info));

                    $this->hasProcessedHeader = true;
                    break;

                case $this->batchCode():
                    if (!$this->hasProcessedHeader)
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de lote sin un registro previo de control, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    if ($this->hasProcessedBatch)
                        throw new Exception(sprintf("El archivo en la línea %d tiene más de un registro de lote anidado, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

                    $batch = $this->parseBatch($info);
                    
                    $this->hasProcessedBatch = true;
                    $this->currentBatch = $batch->batchCode();
                    break;

                case $this->detailCode():
                    if (!$this->hasProcessedBatch)
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de datos sin un registro previo de lote, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

                    $result->addRecord($this->parseDetail($info));

                    break;

                case $this->endBatchCode():
                    if (!$this->hasProcessedBatch)
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote sin un registro previo de lote, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    
                    $batch = $this->parseEndBatch($info);
                    
                    if ($this->currentBatch != $batch->batchCode())
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote que no corresponde al lote abierto, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

                    $this->hasProcessedBatch = false;
                    $this->currentBatch = null;
                    break;

                case $this->controlCode():
                    if (!$this->hasProcessedHeader)
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote sin un registro previo de control, lo cual denota una mala estructura\n%s", $rows, $info), 1003);

                    $control = $this->parseControl($info);
                    
                    $this->hasProcessedHeader = false;
                    break;
            }
        }
        
        $this->closeFile();

        return $result;
    }
}