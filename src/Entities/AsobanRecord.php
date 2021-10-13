<?php

namespace Dnetix\Asoban\Entities;

class AsobanRecord
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

    public function reference()
    {
        return $this->get('reference');
    }

    public function amount()
    {
        return $this->get('amount');
    }

    public function origin()
    {
        return $this->get('origin');
    }

    public function channel()
    {
        return $this->get('channel');
    }

    public function operationId()
    {
        return $this->get('operationId');
    }

    public function authCode()
    {
        return $this->get('authCode');
    }

    public function thirdEntity()
    {
        return $this->get('thirdEntity');
    }

    public function branch()
    {
        return $this->get('branch');
    }

    public function sequence()
    {
        return $this->get('sequence');
    }

    public function refundReason()
    {
        return $this->get('refundReason');
    }
}
