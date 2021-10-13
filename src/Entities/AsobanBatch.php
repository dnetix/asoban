<?php

namespace Dnetix\Asoban\Entities;

class AsobanBatch extends BaseEntity
{
    /**
     * @var AsobanRecord[]
     */
    protected array $records = [];

    private int $amount = 0;
    private int $count = 0;

    public function batchCode(): ?string
    {
        return $this->get('batchCode');
    }

    public function serviceCode(): ?string
    {
        return $this->get('serviceCode');
    }

    public function addRow($data): self
    {
        if (is_array($data)) {
            $data = new AsobanRecord($data);
        }

        $this->amount += (int)($data->amount() * 100);
        $this->count++;

        // Plus one because it should start the sequence on 2 according to docs
        $data->setSequence($this->count + 1);

        $this->records[] = $data;
        return $this;
    }

    public function records(): array
    {
        return $this->records;
    }

    public function endBatch(): AsobanEndBatch
    {
        return new AsobanEndBatch([
            'records' => $this->count,
            'amount' => $this->amount / 100,
            'batchCode' => $this->batchCode(),
        ]);
    }
}
