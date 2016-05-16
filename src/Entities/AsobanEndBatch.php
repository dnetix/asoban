<?php


namespace Dnetix\Asoban\Entities;


class AsobanEndBatch
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function batchCode()
    {
        return $this->get('batchCode');
    }

    public function records()
    {
        return $this->get('records');
    }

    public function amount()
    {
        return $this->get('amount');
    }

}