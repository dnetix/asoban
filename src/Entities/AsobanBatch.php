<?php

namespace Dnetix\Asoban\Entities;

class AsobanBatch extends BaseEntity
{
    public function batchCode(): ?string
    {
        return $this->get('batchCode');
    }

    public function serviceCode(): ?string
    {
        return $this->get('serviceCode');
    }
}
