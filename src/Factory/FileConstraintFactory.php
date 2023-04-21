<?php

declare(strict_types=1);

namespace App\Factory;

use App\Enum\FileType;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\File;

class FileConstraintFactory
{
    public function build(): Collection
    {
        return new Collection([
            'file' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => FileType::all()
                ])
            ]
        ]);
    }
}