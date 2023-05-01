<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class File
{
    public function __construct(
        private string $uuid,
        private int $accountId,
        private array $payload,
        private string $downloadLink,
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

    /**
     * @return string
     */
    public function getDownloadLink(): string
    {
        return $this->downloadLink;
    }
}
