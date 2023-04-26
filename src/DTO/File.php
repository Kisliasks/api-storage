<?php

declare(strict_types=1);

namespace App\DTO;

class File
{
    public function __construct(
        private readonly string $uuid,
        private readonly int $accountId,
        private readonly array $payload
    ) {
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
