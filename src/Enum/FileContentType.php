<?php

declare(strict_types=1);

namespace App\Enum;

enum FileContentType: string
{
    case IMAGE = 'image/gif';
    case TEXT = 'text/plain';
    
    /**
     * @return array
     */
    public static function all(): array
    {
        return array_map(
            function (FileType $fileType) {
                return $fileType->value;
            },
            self::cases()
        );
    }
}
