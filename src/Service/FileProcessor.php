<?php

namespace App\Service;

use SplFileObject;

class FileProcessor
{
    public function process(string $inputFile, string $outputFile, callable $callback): void
    {
        $inputHandle = new SplFileObject($inputFile, 'r');
        $outputHandle = new SplFileObject($outputFile, 'w');

        while (!$inputHandle->eof()) {
            $line = $inputHandle->fgets();
            if ($line !== false) {
                $processedLine = $callback(trim($line));
                $outputHandle->fwrite($processedLine . PHP_EOL);
            }
        }
    }
}