<?php

declare(strict_types=1);

namespace App\Application\Enum;

enum FileExpression: string
{
    case FILE_TOKEN_EXPRESSION = 
        '/(^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12})&name=(\w+)$/';
    
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
