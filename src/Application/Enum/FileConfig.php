<?php

namespace App\Application\Enum;

/**
 * Позволяет настроить директорию хранения файлов,
 * их префикс и др.
 */
enum FileConfig: string
{
    public const BASE_FILE_DIRECTORY = 'files_storage';
    public const APPROVED_FILE_PREFIX = 'approved_';
    /**
     * Устанавливает заглушку на файл
     * @var string
     */
    public const MOCK_FILE_INFO = 'non-existent file info';
    public const HTTP_PROTOCOL = 'http://';
    public const FILE_DOWNLOAD_ROUTE = '/api/files/download/';
    public const FILENAME_TOKEN_PREFIX = '&name=';
    public const DEV_BASE_DOMAIN = 'http://127.0.0.1:8080';
}