<?php


namespace Dnetix\Asoban\Entities;


class AsobanBatch
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

    public function serviceCode()
    {
        return $this->get('serviceCode');
    }

}