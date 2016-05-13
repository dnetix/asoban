<?php


namespace Dnetix\Asoban\Entities;


class AsobanHeader
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function nit()
    {
        return $this->get('nit');
    }

    public function date()
    {
        return $this->get('date');
    }

    public function bankCode()
    {
        return $this->get('bankCode');
    }

    public function accountNumber()
    {
        return $this->get('accountNumber');
    }

    public function fileDate()
    {
        return $this->get('fileDate');
    }

    public function fileTime()
    {
        return $this->get('fileTime');
    }

    public function fileModifier()
    {
        return $this->get('fileModifier');
    }

    public function accountType()
    {
        return $this->get('accountType');
    }
    
}