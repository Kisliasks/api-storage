<?php
declare(strict_types=1);

namespace App\Application\Error;

use App\Application\Interfaces\ProblemJsonErrorInterface;
use Throwable;

abstract class AbstractProblemJsonError extends \DomainException implements ProblemJsonErrorInterface
{
    /** @var int $status */
    protected $status;
    /** @var string $detail */
    protected $detail;
    /** @var string $title */
    protected $title;
    /** @var string $type */
    protected $type;
    /** @var array $additional */
    protected $additional = [];

    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setType();
    }

    public static function create(string $detail = ''): AbstractProblemJsonError
    {
        $e = new static($detail);
        if (!empty($detail)) {
            $e->setDetail($detail);
        }
        $e->setType();

        return $e;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): AbstractProblemJsonError
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type = ''): AbstractProblemJsonError
    {
        if (empty($type)) {
            $class = is_object($this) ? get_class($this) : $this;
            $class_basename = basename(str_replace('\\', '/', $class));

            $type = str_ireplace('Exception', '', $class_basename);
        }
        $this->type = $type;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($message): AbstractProblemJsonError
    {
        $this->title = $message;

        return $this;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function setDetail($message): AbstractProblemJsonError
    {
        $this->detail = $message;

        return $this;
    }

    public function getAdditionalData(): array
    {
        return $this->additional;
    }

    /**
     * Allow serialization via json_encode().
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Serialize the exception to an array of problem details.
     *
     * Likely useful for the JsonSerializable implementation, but also
     * for cases where the XML variant is desired.
     */
    public function toArray(): array
    {
        $problem = [
            'status' => $this->status,
            'detail' => $this->detail,
            'title'  => $this->title,
            'type'   => $this->type,
        ];

        if ($this->additional) {
            $problem = array_merge($this->additional, $problem);
        }

        return $problem;
    }

    public function setInvalidParams(array $errors): AbstractProblemJsonError
    {
        foreach ($errors as $name => $reasons) {
            $this->additional['invalid-params'][] = [
                'name'   => $name,
                'reason' => $reasons,
            ];
        }

        return $this;
    }
}
