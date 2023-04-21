<?php

declare(strict_types=1);

namespace App\Enum;

enum FileType: string
{
    case JPG = 'image/jpg';
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case PDF = 'application/pdf';

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

    /**
     * @param FileType $fileType
     * @return string
     */
    public static function getExt(FileType $fileType): string
    {
        $fileType = explode('/', $fileType->value);

        return $fileType[1];
    }

    /**
     * @return array
     */
    public static function imageTypes(): array
    {
        return [
            self::JPG->value,
            self::JPEG->value,
            self::PNG->value,
        ];
    }

    /**
     * @return string[]
     */
    public static function documentTypes(): array
    {
        return [
            self::PDF->value,
        ];
    }
}
