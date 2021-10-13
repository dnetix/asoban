<?php

namespace Dnetix\Asoban\Parsers;

use Dnetix\Asoban\Entities\AsobanBatch;
use Dnetix\Asoban\Entities\AsobanControl;
use Dnetix\Asoban\Entities\AsobanEndBatch;
use Dnetix\Asoban\Entities\AsobanHandler;
use Dnetix\Asoban\Entities\AsobanHeader;
use Dnetix\Asoban\Entities\AsobanRecord;
use Dnetix\Asoban\Exceptions\AsobanException;
use Exception;

/**
 * Class GenericParser
 * Handles the process.
 */
abstract class GenericParser
{
    protected $fileDescriptor;
    protected $filePath;

    private $hasProcessedHeader = false;
    private $hasProcessedBatch = false;
    private ?AsobanBatch $currentBatch = null;

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

        if (!file_exists($this->filePath())) {
            throw AsobanException::forFileNotFound($filePath);
        }

        $this->fileDescriptor = fopen($filePath, 'rb');

        if ($this->fileDescriptor === false) {
            throw AsobanException::forFileCannotBeRead($filePath);
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

    abstract public function lineLength();

    abstract public function recordType($line);

    abstract public function headerCode();

    abstract public function batchCode();

    abstract public function detailCode();

    abstract public function endBatchCode();

    abstract public function controlCode();

    /**
     * @param $row
     * @return AsobanHeader
     */
    abstract public function parseHeader($row);

    /**
     * @param $row
     * @return AsobanBatch
     */
    abstract public function parseBatch($row);

    /**
     * @param $row
     * @return AsobanRecord
     */
    abstract public function parseDetail($row);

    /**
     * @param $row
     * @return AsobanEndBatch
     */
    abstract public function parseEndBatch($row);

    /**
     * @param $row
     * @return AsobanControl
     */
    abstract public function parseControl($row);

    /**
     * @return AsobanHandler
     * @throws Exception
     */
    public function parse()
    {
        $this->getFileDescriptor();

        $result = new AsobanHandler();

        $rows = 0;

        while (false !== ($info = $this->getNextLine())) {
            $rows++;

            if (empty($info)) {
                continue;
            }

            if (strlen($info) < $this->lineLength()) {
                throw new Exception('Line ' . $rows . ' has invalid length. ' . strlen($info) . ' expected: ' . $this->lineLength());
            }

            $recordType = $this->recordType($info);

            switch ($recordType) {
                case $this->headerCode():
                    if ($this->hasProcessedHeader) {
                        throw new Exception('The file [' . $this->filePath() . '] has more than one header');
                    }

                    $result->addHeader($this->parseHeader($info));

                    $this->hasProcessedHeader = true;
                    break;

                case $this->batchCode():
                    if (!$this->hasProcessedHeader) {
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de lote sin un registro previo de control, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    }
                    if ($this->hasProcessedBatch) {
                        throw new Exception(sprintf("El archivo en la línea %d tiene más de un registro de lote anidado, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    }

                    $batch = $this->parseBatch($info);
                    $result->addBatch($batch);

                    $this->hasProcessedBatch = true;
                    $this->currentBatch = $batch;
                    break;

                case $this->detailCode():
                    if (!$this->hasProcessedBatch) {
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de datos sin un registro previo de lote, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    }

                    $this->currentBatch->addRow($this->parseDetail($info));

                    break;

                case $this->endBatchCode():
                    if (!$this->hasProcessedBatch) {
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote sin un registro previo de lote, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    }

                    $batch = $this->parseEndBatch($info);

                    if ($this->currentBatch->batchCode() != $batch->batchCode()) {
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote que no corresponde al lote abierto, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    }

                    $this->hasProcessedBatch = false;
                    $this->currentBatch = null;
                    break;

                case $this->controlCode():
                    if (!$this->hasProcessedHeader) {
                        throw new Exception(sprintf("El archivo en la línea %d tiene un registro de fin de lote sin un registro previo de control, lo cual denota una mala estructura\n%s", $rows, $info), 1003);
                    }

                    $control = $this->parseControl($info);
                    $result->addControl($control);

                    $this->hasProcessedHeader = false;
                    break;
            }
        }

        $this->closeFile();

        return $result;
    }

    public static function arrayDescriptorAsString(array $row): string
    {
        $line = '';
        foreach ($row as $item) {
            $line .= str_pad($item[0], $item[1], $item[2] ?? 0, STR_PAD_LEFT);
        }
        return $line;
    }
}
