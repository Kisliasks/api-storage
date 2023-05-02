<?php

declare(strict_types=1);

namespace App\Application\Error;

use App\Application\Exception\ValidateException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidateErrorGenerator
{
    /**
     * @param ConstraintViolationListInterface $violations
     * @return string
     */
    public function generate(ConstraintViolationListInterface $violations): void
    {
        $messages = [];
        foreach ($violations as $violation) {
            $path = str_replace('][', '.', trim($violation->getPropertyPath(), '[]'));
            $messages[$path] = $violation->getMessage();
        }

        $exception = ValidateException::create();
        $exception->setInvalidParams($messages);

        throw $exception;
    }
}
