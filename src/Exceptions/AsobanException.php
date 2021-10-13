<?php

namespace Dnetix\Asoban\Exceptions;

use Exception;

class AsobanException extends Exception
{
    public static function forInvalidFileFormat(string $format): self
    {
        return new self('No Asoban parser defined for the format provided ' . $format);
    }

    public static function forFileNotFound(string $path): self
    {
        return new self('File not exists to open the file [' . $path . ']');
    }

    public static function forFileCannotBeRead(string $path): self
    {
        return new self('Failed to open file [' . $path . ']');
    }
}