<?php

namespace Dnetix\Asoban\Entities;

class BaseEntity
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    protected function get($key): ?string
    {
        return $this->data[$key] ?? null;
    }
}