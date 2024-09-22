<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DynamicFormFieldTypeRepository;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: DynamicFormFieldTypeRepository::class)]
#[ApiResource]
class DynamicFormFieldType
{
    #[
        ORM\Id,
        ORM\Column(type: 'bigint', options: ["unsigned" => true])
    ]
    #[Ignore]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $dynamic_form_field_type_code = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $dynamic_form_field_type = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private $dynamic_form_field_configs = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private $dynamic_form_field_validation_configs = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $dynamic_form_field_dependency_parent_configs = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $dynamic_form_field_dependency_child_configs = null;

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

    public function getDynamicFormFieldTypeCode(): ?string
    {
        return $this->dynamic_form_field_type_code;
    }

    public function setDynamicFormFieldTypeCode(string $dynamic_form_field_type_code): static
    {
        $this->dynamic_form_field_type_code = $dynamic_form_field_type_code;

        return $this;
    }

    public function getDynamicFormFieldType(): ?string
    {
        return $this->dynamic_form_field_type;
    }

    public function setDynamicFormFieldType(string $dynamic_form_field_type): static
    {
        $this->dynamic_form_field_type = $dynamic_form_field_type;

        return $this;
    }

    public function getDynamicFormFieldConfigs(): ?array
    {
        return $this->dynamic_form_field_configs;
    }

    public function setDynamicFormFieldConfigs(?array $dynamic_form_field_configs): self
    {
        $this->dynamic_form_field_configs = $dynamic_form_field_configs;

        return $this;
    }

    public function getDynamicFormFieldValidationConfigs(): ?array
    {
        return $this->dynamic_form_field_validation_configs;
    }

    public function setDynamicFormFieldValidationConfigs(?array $dynamic_form_field_validation_configs): self
    {
        $this->dynamic_form_field_validation_configs = $dynamic_form_field_validation_configs;

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

    public function setCreateUser(?string $create_user): static
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

    public function getDynamicFormFieldDependencyParentConfigs(): ?array
    {
        return $this->dynamic_form_field_dependency_parent_configs;
    }

    public function setDynamicFormFieldDependencyParentConfigs(?array $dynamic_form_field_dependency_parent_configs): static
    {
        $this->dynamic_form_field_dependency_parent_configs = $dynamic_form_field_dependency_parent_configs;

        return $this;
    }

    public function getDynamicFormFieldDependencyChildConfigs(): ?array
    {
        return $this->dynamic_form_field_dependency_child_configs;
    }

    public function setDynamicFormFieldDependencyChildConfigs(?array $dynamic_form_field_dependency_child_configs): static
    {
        $this->dynamic_form_field_dependency_child_configs = $dynamic_form_field_dependency_child_configs;

        return $this;
    }

}
