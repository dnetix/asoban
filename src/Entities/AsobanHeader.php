<?php

namespace Dnetix\Asoban\Entities;

/**
 * Class AsobanHeader
 * Contains the plain text report header's information.
 */
class AsobanHeader extends BaseEntity
{
    public const AC_SAVINGS = 1;
    public const AC_CHECKING = 2;
    public const AC_CARD = 3;

    public function nit(): ?string
    {
        return $this->get('nit');
    }

    /**
     * Returns a Ymd.
     */
    public function date(): ?string
    {
        return $this->get('date');
    }

    /**
     * Codigo Entidad Recaudadora.
     */
    public function bankCode(): ?string
    {
        return $this->get('bankCode');
    }

    public function accountNumber(): ?string
    {
        return $this->get('accountNumber');
    }

    /**
     * Returns a Ymd with the date when the file was created.
     */
    public function fileDate(): ?string
    {
        return $this->get('fileDate');
    }

    /**
     * Returns the time with a format HHMM representing the time when
     * the file was created.
     */
    public function fileTime(): ?string
    {
        return $this->get('fileTime');
    }

    public function fileModifier(): ?string
    {
        return $this->get('fileModifier');
    }

    /**
     * Returns
     *  01: Savings Account
     *  02: Checking Account
     *  03: Credit Card.
     */
    public function accountType(): ?string
    {
        return $this->get('accountType');
    }
}
