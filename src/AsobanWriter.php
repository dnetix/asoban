<?php

namespace Dnetix\Asoban;

use Dnetix\Asoban\Entities\AsobanHandler;
use Dnetix\Asoban\Parsers\Format2001;

class AsobanWriter extends AsobanHandler
{
    private string $filePath;
    private $file;
    private string $format;

    public function __construct(string $filePath, string $format = '2001')
    {
        $this->filePath = $filePath;
        $this->format = $format;
    }

    public static function create(string $filePath, string $format = '2001')
    {
        return new self($filePath, $format);
    }

    public function generate()
    {
        $this->file = fopen($this->filePath, 'w+');

        $formatter = new Format2001($this->filePath);

        $this->writeLine($formatter->headerLine($this->header()));

        foreach ($this->batchs as $batch) {
            $amount = 0;
            $count = 0;
            $this->writeLine($formatter->batchLine($batch));
            foreach ($batch->records() as $record) {
                $amount += $record->amount();
                $count++;
                $this->writeLine($formatter->recordLine($record));
            }
            $this->writeLine($formatter->endBatchLine($batch->endBatch()));
        }

        fclose($this->file);
    }

    private function writeLine($content)
    {
        fwrite($this->file, $content . "\n");
    }
}
