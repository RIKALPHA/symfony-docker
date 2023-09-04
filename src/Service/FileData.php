<?php

namespace App\Service;

use DateTimeImmutable;

class FileData
{
    private const LOCAL_STORAGE = 'data/files/';
    private string $fileToday;

    public function __construct(
    )
    {
        $this->fileToday = self::LOCAL_STORAGE . (new DateTimeImmutable())->format('Y-m-d');
    }

    public function getFileName(): string
    {
        return $this->fileToday;
    }

    public function fileExists($name): bool
    {
        if (file_exists($name)) {
            return true;
        }

        return false;
    }

}
