<?php

namespace Dnetix\Asoban\Entities;

use Cassandra\Date;
use DateTime;

/**
 * Class AsobanHeader
 * Contains the plain text report header's information.
 */
class AsobanHeader extends BaseEntity
{
    public function nit(): ?string
    {
        return $this->get('nit');
    }

    /**
     * Returns a Y-m-d.
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
     * Returns a Y-m-d with the date when the file was created.
     */
    public function fileDate(): ?string
    {
        return $this->get('fileDate');
    }

    /**
     * Returns the time with a format HH:MM representing the time when
     * the file was created.
     */
    public function fileTime(): ?string
    {
        return substr_replace($this->get('fileTime'), ':', 2, 0);
    }

    /**
     * @return DateTime
     */
    public function fileDateTime(): DateTime
    {
        return new DateTime($this->fileDate() . ' ' . $this->fileTime());
    }

    public function fileModifier(): ?string
    {
        return $this->get('fileModifier');
    }

    /**
     * Returns
     *  1: Savings Account
     *  2: Checking Account
     *  3: Credit Card.
     */
    public function accountType(): ?string
    {
        return $this->get('accountType');
    }
}
