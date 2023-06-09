<?php

namespace App\FileModule\Entity;

use App\Application\DTO\File as DTOFile;
use App\FileModule\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $uuid;

    #[ORM\Column]
    private array $payload;

    #[ORM\Column]
    private int $account_id;

    #[ORM\Column(length: 100)]
    private string $download_link;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getAccountId(): int
    {
        return $this->account_id;
    }

    public function setAccountId(int $account_id): self
    {
        $this->account_id = $account_id;

        return $this;
    }

    public function toDto(): DTOFile
    {
        return new DTOFile(
            $this->getUuid(),
            $this->getAccountId(),
            $this->getPayload(),
            $this->getDownloadLink(),
        );
    }

    public function getDownloadLink(): string
    {
        return $this->download_link;
    }

    public function setDownloadLink(string $download_link): self
    {
        $this->download_link = $download_link;

        return $this;
    }
}
