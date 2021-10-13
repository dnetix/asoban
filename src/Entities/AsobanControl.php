<?php

namespace Dnetix\Asoban\Entities;

class AsobanControl extends BaseEntity
{
    public function records()
    {
        return $this->get('records');
    }

    public function amount()
    {
        return $this->get('amount');
    }
}
