<?php


namespace Dnetix\Asoban\Entities;


class AsobanResult
{

    /**
     * @var AsobanHeader
     */
    protected $header;
    protected $records = [];

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