<?php

declare(strict_types=1);

namespace App\Factory;

use App\Enum\FileType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class FileConstraintFactory
{
    public function build(): Collection
    {
        return new Collection([
            'file' => [
                new Type('string'),
                new File([
                    'maxSize' => '1100000',
                    'mimeTypes' => FileType::all(),
                    'extensions' => [
                        FileType::getExt(FileType::JPEG),
                        FileType::getExt(FileType::JPG),
                        FileType::getExt(FileType::PNG),
                        FileType::getExt(FileType::PDF),
                    ],
                ])
            ],
            'file_name' => [
                new Type('string'),
                new Length(['min' => '1', 'max' => '50'])
            ],
            'file_size' => [
                new Range(['max' => '3145728'])
            ],
            'mime_type' => [
                new Type('string'),
                new Choice(FileType::all())
            ]
        ]);
    }
}