<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TemporaryFileUploadRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: TemporaryFileUploadRepository::class),
    ORM\HasLifecycleCallbacks,
    ORM\Table(name: "temporary_file_upload"),
    ApiResource,
]
class TemporaryFileUpload
{
    #[
        ORM\Id,
        ORM\Column(type: Types::BIGINT, options: ["unsigned" => true])
    ]
    private ?string $id = null;

    #[ORM\Column(type: Types::BIGINT, options: ["unsigned" => true], nullable: true)]
    private ?string $id_parrent_service = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $uploaded_datetime = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    #[ORM\Column]
    private array $other_value = [];

    #[ORM\Column]
    private ?bool $flag_active = null;

    #[ORM\Column(length: 50, options: ['default' => 'system'])]
    private ?string $create_user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(length: 50, options: ['default' => 'system'])]
    private ?string $update_user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->flag_active = true;
        $this->created_at = new \DateTimeImmutable();
        $this->create_user = 'system';
        $this->updated_at = new \DateTimeImmutable();
        $this->update_user = 'system';
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new \DateTimeImmutable();
        $this->update_user = 'system';
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getIdParrentService(): ?string
    {
        return $this->id_parrent_service;
    }

    public function setIdParrentService(string $id_parrent_service): static
    {
        $this->id_parrent_service = $id_parrent_service;

        return $this;
    }

    public function getUploadedDatetime(): ?\DateTimeInterface
    {
        return $this->uploaded_datetime;
    }

    public function setUploadedDatetime(\DateTimeInterface $uploaded_datetime): static
    {
        $this->uploaded_datetime = $uploaded_datetime;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getOtherValue(): array
    {
        return $this->other_value;
    }

    public function setOtherValue(array $other_value): static
    {
        $this->other_value = $other_value;

        return $this;
    }

    public function isFlagActive(): ?bool
    {
        return $this->flag_active;
    }

    public function setFlagActive(bool $flag_active): static
    {
        $this->flag_active = $flag_active;

        return $this;
    }

    public function getCreateUser(): ?string
    {
        return $this->create_user;
    }

    public function setCreateUser(string $create_user): static
    {
        $this->create_user = $create_user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdateUser(): ?string
    {
        return $this->update_user;
    }

    public function setUpdateUser(string $update_user): static
    {
        $this->update_user = $update_user;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }
}
