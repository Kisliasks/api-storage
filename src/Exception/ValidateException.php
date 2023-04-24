<?php

declare(strict_types=1);

namespace App\Exception;

use App\Error\AbstractProblemJsonError;

class ValidateException extends AbstractProblemJsonError
{
    /** @var int $code */
    protected $code = 400;
    /** @var string $detail */
    protected $detail = 'Validate error';
    /** @var string $title */
    protected $title = 'Validate exception';
}
