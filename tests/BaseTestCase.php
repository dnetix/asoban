<?php

class BaseTestCase extends PHPUnit_Framework_TestCase
{

    public function generateFile()
    {
        $rows = [
            [
                ['01', 2],
                ['12345', 10],
                ['20161201', 8],
                ['ABC', 3],
                ['00849514521', 17, ' '],
                ['20161201', 8],
                ['0800', 4],
                ['Z', 1],
                ['1', 2],
                ['', 106, ' ']
            ],
            [
                ['05', 2],
                ['123456', 13],
                ['1', 4],
                ['', 142, ' ']
            ],
            [
                ['06', 2],
                ['1040035062', 48],
                ['1000200', 14],
                ['99', 2],
                ['15', 2],
                ['123456', 6],
                ['123456', 6],
                ['PSE', 3],
                ['1234', 4],
                ['2000000', 7],
                ['', 3, ' '],
                ['', 65, ' ']
            ]
        ];

        $file = [];

        foreach ($rows as $row){
            $x = "";
            foreach ($row as $item){
                $x .= str_pad($item[0], $item[1], isset($item[2]) ? $item[2] : 0, STR_PAD_LEFT);
            }
            $file[] = $x;
        }
        
        file_put_contents(__DIR__ . '/files/mocked-correct-file', implode("\n", $file));
        return __DIR__ . '/files/mocked-correct-file';
    }

}