<?php

namespace App\Entity;

use App\Repository\FormRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormRepository::class)]
class Form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'bigint')]
    private $id_form_version;

    #[ORM\Column(type: 'bigint', nullable: true)]
    private $id_form_parent;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $label_field;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $field_name;

    #[ORM\Column(type: 'string', length: 100)]
    private $field_type;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $field_class;

    #[ORM\Column(type: 'string', length: 100)]
    private $field_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $field_placeholder;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $error_message;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $validation_config;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'json', nullable: true)]
    private $dependency_child = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private $dependency_parent = [];

    #[ORM\Column(type: 'smallint')]
    private $flag_required;

    #[ORM\Column(type: 'guid')]
    private $uuid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFormVersion(): ?string
    {
        return $this->id_form_version;
    }

    public function setIdFormVersion(string $id_form_version): self
    {
        $this->id_form_version = $id_form_version;

        return $this;
    }

    public function getIdFormParent(): ?string
    {
        return $this->id_form_parent;
    }

    public function setIdFormParent(?string $id_form_parent): self
    {
        $this->id_form_parent = $id_form_parent;

        return $this;
    }

    public function getLabelField(): ?string
    {
        return $this->label_field;
    }

    public function setLabelField(?string $label_field): self
    {
        $this->label_field = $label_field;

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

    public function getFieldType(): ?string
    {
        return $this->field_type;
    }

    public function setFieldType(string $field_type): self
    {
        $this->field_type = $field_type;

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

    public function getFieldId(): ?string
    {
        return $this->field_id;
    }

    public function setFieldId(string $field_id): self
    {
        $this->field_id = $field_id;

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

    public function getErrorMessage(): ?string
    {
        return $this->error_message;
    }

    public function setErrorMessage(?string $error_message): self
    {
        $this->error_message = $error_message;

        return $this;
    }

    public function getValidationConfig(): ?string
    {
        return $this->validation_config;
    }

    public function setValidationConfig(?string $validation_config): self
    {
        $this->validation_config = $validation_config;

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

    public function getFlagRequired(): ?int
    {
        return $this->flag_required;
    }

    public function setFlagRequired(int $flag_required): self
    {
        $this->flag_required = $flag_required;

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
}
