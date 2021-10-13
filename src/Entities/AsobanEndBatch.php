<?php

namespace Dnetix\Asoban\Entities;

class AsobanEndBatch extends BaseEntity
{
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
