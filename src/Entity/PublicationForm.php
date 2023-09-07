<?php

namespace App\Entity;

use App\Repository\PublicationFormRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PublicationFormRepository::class)]
#[Table(name: 'publication_form')]
class PublicationForm
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    //#[Ignore]
    private $id;

    #[ORM\Column(type: 'bigint')]
    //#[Ignore]
    private $form_version_id;

    #[ORM\Column(type: 'bigint', nullable: true)]
    //#[Ignore]
    private $form_parent_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    //#[Ignore]
    private $field_label;

    #[ORM\Column(type: 'string', length: 100)]
    //#[Ignore]
    private $field_type;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    //#[Ignore]
    private $field_name;

    #[ORM\Column(type: 'string', length: 100)]
    //#[Ignore]
    private $field_id;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    //#[Ignore]
    private $field_class;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    //#[Ignore]
    private $field_placeholder;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Ignore]
    private $field_options;

    #[ORM\Column(type: 'json', nullable: true)]
    //#[Ignore]
    private $field_configs = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    //#[Ignore]
    private $description;

    #[ORM\Column(type: 'integer', nullable: true)]
    //#[Ignore]
    private $order_position;

    #[ORM\Column(type: 'json', nullable: true)]
    //#[Ignore]
    private $validation_configs = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    //#[Ignore]
    private $error_message;

    #[ORM\Column(type: 'json', nullable: true)]
    //#[Ignore]
    private $dependency_child = [];

    #[ORM\Column(type: 'json', nullable: true)]
    //#[Ignore]
    private $dependency_parent = [];

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    //#[Ignore]
    private $flag_required;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $flag_field_publication_type = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $flag_field_title = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $flag_field_publication_date = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Ignore]
    private $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    private $created_user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Ignore]
    private $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    private $updated_user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Ignore]
    private $updated_at;

    #[ORM\Column(type: 'guid', nullable: false)]
    private $uuid;

    #[ORM\ManyToOne(targetEntity: PublicationFormVersion::class, inversedBy: 'forms')]
    #[Ignore]
    private $publication_form_version;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->created_at = new \DateTime("now");
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new \DateTime("now");
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFormVersionId(): ?string
    {
        return $this->form_version_id;
    }

    public function setFormVersionId(string $form_version_id): self
    {
        $this->form_version_id = $form_version_id;

        return $this;
    }

    public function getFormParentId(): ?string
    {
        return $this->form_parent_id;
    }

    public function setFormParentId(?string $form_parent_id): self
    {
        $this->form_parent_id = $form_parent_id;

        return $this;
    }

    public function getFieldLabel(): ?string
    {
        return $this->field_label;
    }

    public function setFieldLabel(?string $field_label): self
    {
        $this->field_label = $field_label;

        return $this;
    }

    public function getFieldType(): ?string
    {
        return $this->field_type;
    }

    public function setFieldType(string $field_type): self
    {
        $this->field_type = $field_type;

        return $this;
    }

    public function getFieldName(): ?string
    {
        return $this->field_name;
    }

    public function setFieldName(?string $field_name): self
    {
        $this->field_name = $field_name;

        return $this;
    }

    public function getFieldId(): ?string
    {
        return $this->field_id;
    }

    public function setFieldId(string $field_id): self
    {
        $this->field_id = $field_id;

        return $this;
    }

    public function getFieldClass(): ?string
    {
        return $this->field_class;
    }

    public function setFieldClass(?string $field_class): self
    {
        $this->field_class = $field_class;

        return $this;
    }

    public function getFieldPlaceholder(): ?string
    {
        return $this->field_placeholder;
    }

    public function setFieldPlaceholder(?string $field_placeholder): self
    {
        $this->field_placeholder = $field_placeholder;

        return $this;
    }

    public function getFieldOptions(): ?string
    {
        return $this->field_options;
    }

    public function setFieldOptions(?string $field_options): self
    {
        $this->field_options = $field_options;

        return $this;
    }

    public function getFieldConfigs(): ?array
    {
        return $this->field_configs;
    }

    public function setFieldConfigs(?array $field_configs): self
    {
        $this->field_configs = $field_configs;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOrderPosition(): ?int
    {
        return $this->order_position;
    }

    public function setOrderPosition(?int $order_position): self
    {
        $this->order_position = $order_position;

        return $this;
    }

    public function getValidationConfigs(): ?array
    {
        return $this->validation_configs;
    }

    public function setValidationConfigs(?array $validation_configs): self
    {
        $this->validation_configs = $validation_configs;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->error_message;
    }

    public function setErrorMessage(?string $error_message): self
    {
        $this->error_message = $error_message;

        return $this;
    }

    public function getDependencyChild(): ?array
    {
        return $this->dependency_child;
    }

    public function setDependencyChild(?array $dependency_child): self
    {
        $this->dependency_child = $dependency_child;

        return $this;
    }

    public function getDependencyParent(): ?array
    {
        return $this->dependency_parent;
    }

    public function setDependencyParent(?array $dependency_parent): self
    {
        $this->dependency_parent = $dependency_parent;

        return $this;
    }

    public function getFlagRequired(): ?bool
    {
        return $this->flag_required;
    }

    public function setFlagRequired(bool $flag_required): self
    {
        $this->flag_required = $flag_required;

        return $this;
    }

    public function isFlagFieldPublicationType(): ?bool
    {
        return $this->flag_field_publication_type;
    }

    public function setFlagFieldPublicationType(bool $flag_field_publication_type): self
    {
        $this->flag_field_publication_type = $flag_field_publication_type;

        return $this;
    }

    public function isFlagFieldTitle(): ?bool
    {
        return $this->flag_field_title;
    }

    public function setFlagFieldTitle(bool $flag_field_title): self
    {
        $this->flag_field_title = $flag_field_title;

        return $this;
    }

    public function isFlagFieldPublicationDate(): ?bool
    {
        return $this->flag_field_publication_date;
    }

    public function setFlagFieldPublicationDate(bool $flag_field_publication_date): self
    {
        $this->flag_field_publication_date = $flag_field_publication_date;

        return $this;
    }

    #[Ignore]
    public function getFlagActive(): ?bool
    {
        return $this->flag_active;
    }

    public function setFlagActive(bool $flag_active): self
    {
        $this->flag_active = $flag_active;

        return $this;
    }

    #[Ignore]
    public function getCreatedUser(): ?string
    {
        return $this->created_user;
    }

    public function setCreatedUser(?string $created_user): self
    {
        $this->created_user = $created_user;

        return $this;
    }

    #[Ignore]
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    #[Ignore]
    public function getUpdatedUser(): ?string
    {
        return $this->updated_user;
    }

    public function setUpdatedUser(?string $updated_user): self
    {
        $this->updated_user = $updated_user;

        return $this;
    }

    #[Ignore]
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
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

    public function getPublicationFormVersion(): ?PublicationFormVersion
    {
        return $this->publication_form_version;
    }

    public function setPublicationFormVersion(?PublicationFormVersion $publication_form_version): self
    {
        $this->publication_form_version = $publication_form_version;

        return $this;
    }

}