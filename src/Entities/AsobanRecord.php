<?php

namespace Dnetix\Asoban\Entities;

class AsobanRecord extends BaseEntity
{
    /**
     * PAGO A TRAVÉS DE BANCOS.
     */
    public const SOURCE_BANK = '01';
    /**
     * PAGO A TRAVÉS DE CORPORACIÓN DE AHORRO Y VIVIENDA.
     */
    public const SOURCE_SAVINGS = '02';
    /**
     * PAGO A TRAVÉS DE ACH COLOMBIA.
     */
    public const SOURCE_ACH = '03';
    /**
     * PAGO A TRAVÉS DE ASCREDIBANCO.
     */
    public const SOURCE_CREDIBANCO = '04';
    /**
     * PAGO A TRAVÉS DE ATH.
     */
    public const SOURCE_ATH = '05';
    /**
     * PAGO A TRAVES DE CENIT.
     */
    public const SOURCE_CENIT = '06';
    /**
     * PAGO A TRAVÉS DE RED MULTICOLOR.
     */
    public const SOURCE_RBM = '07';
    /**
     * PAGO A TRAVÉS DE SERVIBANCA.
     */
    public const SOURCE_SERVIBANCA = '08';

    /**
     * POR VENTANILLA EN EFECTIVO.
     */
    public const PM_CASH = '1';
    /**
     * POR VENTANILLA EN CHEQUE.
     */
    public const PM_CHECK = '2';
    /**
     * POR BUZON DE AUTOSERVICIO.
     */
    public const PM_AUTOSERVICE = '3';
    /**
     * DÉBITO EN CUENTA POR SISTEMA DE AUDIORESPUESTA.
     */
    public const PM_IVR = '11';
    /**
     * DEBITO EN CUENTA POR CAJERO ELECTRÓNICO.
     */
    public const PM_ATM = '12';
    /**
     * DEBITO EN CUENTA POR DATÁFONO.
     */
    public const PM_DATAPHONE = '13';
    /**
     * DÉBITO EN CUENTA POR DOMICILIACIÓN.
     */
    public const PM_PPD = '14';
    /**
     * DÉBITO EN CUENTA POR INTERNET.
     */
    public const PM_INTERNET_DEBIT = '15';
    /**
     * TARJETA CRÉDITO POR SISTEMA DE AUDIORESPUESTA.
     */
    public const PM_CARD_IVR = '21';
    /**
     * TARJETA CRÉDITO POR CAJERO ELECTRÓNICO.
     */
    public const PM_CARD_ATM = '22';
    /**
     * TARJETA CRÉDITO POR DATÁFONO.
     */
    public const PM_CARD_DATAPHONE = '23';
    /**
     * TARJETA CRÉDITO POR DOMICILIACIÓN.
     */
    public const PM_CARD_TOKEN = '24';
    /**
     * TARJETA CRÉDITO POR INTERNET.
     */
    public const PM_CARD_INTERNET = '25';

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

    public function setSequence(string $sequence): self
    {
        $this->data['sequence'] = $sequence;
        return $this;
    }
}
