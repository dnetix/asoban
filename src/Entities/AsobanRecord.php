<?php

namespace Dnetix\Asoban\Entities;

class AsobanRecord extends BaseEntity
{
    public function reference(): ?string
    {
        return $this->get('reference');
    }

    public function amount(): ?string
    {
        return $this->get('amount');
    }

    public function origin(): ?string
    {
        return $this->get('origin');
    }

    public function channel(): ?string
    {
        return $this->get('channel');
    }

    public function operationId(): ?string
    {
        return $this->get('operationId');
    }

    public function authCode(): ?string
    {
        return $this->get('authCode');
    }

    public function thirdEntity(): ?string
    {
        return $this->get('thirdEntity');
    }

    public function branch(): ?string
    {
        return $this->get('branch');
    }

    public function sequence(): ?string
    {
        return $this->get('sequence');
    }

    public function refundReason(): ?string
    {
        return $this->get('refundReason');
    }
}
