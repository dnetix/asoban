<?php


namespace Dnetix\Asoban\Entities;


/**
 * Class AsobanHeader
 * Contains the plain text report header's information
 */
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

    /**
     * @return string
     */
    public function nit()
    {
        return $this->get('nit');
    }

    /**
     * Returns a Y-m-d
     * @return string
     */
    public function date()
    {
        return $this->get('date');
    }

    /**
     * Codigo Entidad Recaudadora
     * @return string
     */
    public function bankCode()
    {
        return $this->get('bankCode');
    }

    /**
     * @return string
     */
    public function accountNumber()
    {
        return $this->get('accountNumber');
    }

    /**
     * Returns a Y-m-d with the date when the file was created
     * @return string
     */
    public function fileDate()
    {
        return $this->get('fileDate');
    }

    /**
     * Returns the time with a format HHMM representing the time when
     * the file was created
     * @return string
     */
    public function fileTime()
    {
        return $this->get('fileTime');
    }

    public function fileModifier()
    {
        return $this->get('fileModifier');
    }

    /**
     * Returns
     *  1: Savings Account
     *  2: Checking Account
     *  3: Credit Card
     * @return int
     */
    public function accountType()
    {
        return $this->get('accountType');
    }
    
}