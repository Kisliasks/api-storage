<?php

namespace App\Enum;

/**
 * Позволяет настроить директорию хранения файлов,
 * их префикс и др.
 */
enum FileConfig
{
    public const BASE_FILE_DIRECTORY = 'files_storage';
    public const APPROVED_FILE_PREFIX = 'approved_';
    /**
     * Устанавливает заглушку на файл
     * @var string
     */
    public const MOCK_FILE_INFO = 'non-existent file info';
}