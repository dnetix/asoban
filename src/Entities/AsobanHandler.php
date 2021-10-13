<?php

namespace Dnetix\Asoban\Entities;

class AsobanHandler
{
    protected ?AsobanHeader $header = null;
    /**
     * @var AsobanBatch[] $batchs
     */
    protected array $batchs = [];
    protected ?AsobanControl $control = null;

    protected int $count = 0;

    public function addHeader($header): AsobanHeader
    {
        if (is_array($header)) {
            $header = new AsobanHeader($header);
        }
        $this->header = $header;
        return $this->header;
    }

    public function addBatch($batch): AsobanBatch
    {
        $this->count++;

        if (is_array($batch)) {
            if ($batch['batchCode'] ?? false) {
                $this->count = (int)$batch['batchCode'];
            } else {
                $batch['batchCode'] = $this->count;
            }
            $batch = new AsobanBatch($batch);
        }
        $this->batchs[] = $batch;
        return $batch;
    }

    public function addControl(AsobanControl $control)
    {
        $this->control = $control;
        return $this;
    }

    public function header(): ?AsobanHeader
    {
        return $this->header;
    }

    /**
     * @return AsobanBatch[]
     */
    public function batchs(): array
    {
        return $this->batchs;
    }

    public function control(): ?AsobanControl
    {
        return $this->control;
    }
}
