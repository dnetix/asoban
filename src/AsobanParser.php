<?php

namespace Dnetix\Asoban;

use Dnetix\Asoban\Entities\AsobanResult;
use Dnetix\Asoban\Parsers\Format1998;
use Dnetix\Asoban\Parsers\Format2001;
use Exception;

class AsobanParser
{
    public const F_2001 = '2001';
    public const F_1998 = '1998';

    public static $FORMATS = [
        self::F_2001 => 'Asobancaria 2001',
        self::F_1998 => 'Asobancaria 1998',
    ];

    private $filePath;
    /**
     * @var string
     */
    private $format;

    public function __construct($filePath, $format = '2001')
    {
        $this->filePath = $filePath;
        $this->format = $format;
    }

    public static function load($filePath)
    {
        return new self($filePath);
    }

    /**
     * @return AsobanResult
     * @throws Exception
     */
    public function parse()
    {
        if ($this->format == self::F_2001) {
            return (new Format2001($this->filePath))->parse();
        } elseif ($this->format == self::F_1998) {
            return (new Format1998($this->filePath))->parse();
        } else {
            throw new Exception('No Asoban parser defined for the format provided');
        }
    }
}
