<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DynamicFormFieldOptionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: DynamicFormFieldOptionsRepository::class)]
#[ApiResource]
class DynamicFormFieldOptions
{
    #[
        ORM\Id,
        ORM\Column(type: 'bigint', options: ["unsigned" => true])
    ]
    #[Ignore]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    private ?string $dynamic_form_field_options_type = null;

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private ?string $dynamic_form_field_options_code = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $dynamic_form_field_options = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Ignore]
    private ?bool $flag_active;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => 'system'])]
    #[Ignore]
    private ?string $create_user;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Ignore]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => 'system'])]
    #[Ignore]
    private ?string $update_user;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Ignore]
    private ?\DateTimeInterface $updated_at;

    #[ORM\Column(type: Types::GUID, nullable: false)]
    private ?string $uuid;

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

    public function getDynamicFormFieldOptionsType(): ?string
    {
        return $this->dynamic_form_field_options_type;
    }

    public function setDynamicFormFieldOptionsType(string $dynamic_form_field_options_type): static
    {
        $this->dynamic_form_field_options_type = $dynamic_form_field_options_type;

        return $this;
    }

    public function getDynamicFormFieldOptionsCode(): ?string
    {
        return $this->dynamic_form_field_options_code;
    }

    public function setDynamicFormFieldOptionsCode(string $dynamic_form_field_options_code): static
    {
        $this->dynamic_form_field_options_code = $dynamic_form_field_options_code;

        return $this;
    }

    public function getDynamicFormFieldOptions(): ?string
    {
        return $this->dynamic_form_field_options;
    }

    public function setDynamicFormFieldOptions(string $dynamic_form_field_options): static
    {
        $this->dynamic_form_field_options = $dynamic_form_field_options;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    #[Ignore]
    public function isFlagActive(): ?bool
    {
        return $this->flag_active;
    }

    public function setFlagActive(bool $flag_active): static
    {
        $this->flag_active = $flag_active;

        return $this;
    }

    #[Ignore]
    public function getCreateUser(): ?string
    {
        return $this->create_user;
    }

    public function setCreateUser(string $create_user): static
    {
        $this->create_user = $create_user;

        return $this;
    }

    #[Ignore]
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    #[Ignore]
    public function getUpdateUser(): ?string
    {
        return $this->update_user;
    }

    public function setUpdateUser(string $update_user): static
    {
        $this->update_user = $update_user;

        return $this;
    }

    #[Ignore]
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
