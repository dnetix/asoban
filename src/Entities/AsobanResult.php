<?php


namespace Dnetix\Asoban\Entities;


class AsobanResult
{

    /**
     * @var AsobanHeader
     */
    protected $header;
    protected $records = [];
    protected $batchs = [];

    public function __construct()
    {
    }

    public function addHeader(AsobanHeader $asobanHeader)
    {
        $this->header = $asobanHeader;
    }

    public function addRecord(AsobanRecord $record)
    {
        $this->records[] = $record;
    }
    
    public function addBatch(AsobanBatch $batch)
    {
        $this->batchs[] = $batch;
    }

    /**
     * Returns all the records parsed
     * @return array
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @return AsobanHeader
     */
    public function header()
    {
        return $this->header;
    }

}