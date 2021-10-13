<?php

namespace Dnetix\Asoban\Entities;

class AsobanHandler
{
    /**
     * @var AsobanHeader
     */
    protected $header;
    protected $records = [];
    protected $batchs = [];
    protected $control;

    public function addHeader(AsobanHeader $asobanHeader)
    {
        $this->header = $asobanHeader;
        return $this;
    }

    public function addRecord(AsobanRecord $record)
    {
        $this->records[] = $record;
        return $this;
    }

    public function addBatch(AsobanBatch $batch)
    {
        $this->batchs[] = $batch;
        return $this;
    }

    public function addControl($control)
    {
        $this->control = $control;
        return $this;
    }

    /**
     * Returns all the records parsed.
     * @return AsobanRecord[]
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

    /**
     * @return AsobanControl
     */
    public function control()
    {
        return $this->control;
    }

    /**
     * Returns the number of records for this result.
     * @return int
     */
    public function recordCount()
    {
        return count($this->getRecords());
    }
}
