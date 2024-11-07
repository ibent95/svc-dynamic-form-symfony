<?php

namespace App\Entity;

use App\Repository\PublicationFormRepository;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[
    ORM\Entity(repositoryClass: PublicationFormRepository::class),
    ORM\HasLifecycleCallbacks,
    ORM\Table(name: 'publication_form'),
    ApiResource
]
class PublicationForm
{
    #[
        ORM\Id,
        ORM\Column(type: 'bigint', options: ["unsigned" => true], unique: true)
    ]
    #[Groups(['internal'])]
    private $id;

    #[ORM\Column(type: 'bigint', options: ["unsigned" => true], nullable: true)]
    private $id_form_version;

    #[ORM\Column(type: 'bigint', options: ["unsigned" => true], nullable: true)]
    #[Groups(['internal'])]
    private $id_form_parent;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['public', 'internal'])]
    private $field_label;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['public', 'internal'])]
    private $field_type;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Groups(['public', 'internal'])]
    private $field_name;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['public', 'internal'])]
    private $field_id;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Groups(['public', 'internal'])]
    private $field_class;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['public', 'internal'])]
    private $field_placeholder;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['public', 'internal'])]
    private $field_options;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['public', 'internal'])]
    private $field_configs = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['public', 'internal'])]
    private $description;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['internal'])]
    private $order_position;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['public', 'internal'])]
    private $validation_configs = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['public', 'internal'])]
    private $error_message;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['public', 'internal'])]
    private $dependency_child = [];

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['public', 'internal'])]
    private $dependency_parent = [];

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    #[Groups(['public', 'internal'])]
    private $flag_required;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['public', 'internal'])]
    private ?bool $flag_field_form_type = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['public', 'internal'])]
    private ?bool $flag_field_title = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['public', 'internal'])]
    private ?bool $flag_field_publish_date = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Groups(['internal'])]
    private $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups(['internal'])]
    private $create_user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Groups(['internal'])]
    private $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups(['internal'])]
    private $update_user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Groups(['internal'])]
    private $updated_at;

    #[ORM\Column(type: 'guid', nullable: false, unique:true)]
    #[Groups(['public', 'internal'])]
    private $uuid;

    #[
        ORM\ManyToOne(targetEntity: PublicationFormVersion::class, inversedBy: 'forms', fetch: 'LAZY'),
        ORM\JoinColumn(name: 'id_form_version', referencedColumnName: 'id', onDelete:"CASCADE")
    ]
    #[Groups(['public', 'internal'])]
    private ?PublicationFormVersion $form_version;

    #[
        ORM\ManyToOne(targetEntity: PublicationForm::class, inversedBy: 'form_children', fetch: 'LAZY'),
        ORM\JoinColumn(name: 'id_form_parent', referencedColumnName: 'id', onDelete: "CASCADE")
    ]
    #[Groups(['public', 'internal'])]
    private ?self $form_parent = null;

    #[ORM\OneToMany(
        mappedBy: 'form_parent',
        targetEntity: self::class,
        cascade: ["ALL"],
        orphanRemoval: true,
        fetch: 'LAZY'
    )]
    private ?Collection $form_children;

    #[ORM\OneToMany(
        mappedBy: 'form',
        targetEntity: PublicationMeta::class,
        cascade: ["ALL"],
        orphanRemoval: true,
        fetch: 'LAZY',
    )]
    #[Groups(['publication_form-publication_metas'])]
    private Collection $publicationMeta;

    public function __construct()
    {
        $this->publicationMeta = new ArrayCollection();
        $this->form_children = new ArrayCollection();
    }

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

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getIdFormVersion(): ?string
    {
        return $this->id_form_version;
    }

    public function setIdFormVersion(?string $id_form_version): self
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

    public function getFlagFieldFormType(): ?bool
    {
        return $this->flag_field_form_type;
    }

    public function setFlagFieldFormType(bool $flag_field_form_type): self
    {
        $this->flag_field_form_type = $flag_field_form_type;

        return $this;
    }

    public function getFlagFieldTitle(): ?bool
    {
        return $this->flag_field_title;
    }

    public function setFlagFieldTitle(bool $flag_field_title): self
    {
        $this->flag_field_title = $flag_field_title;

        return $this;
    }

    public function getFlagFieldPublishDate(): ?bool
    {
        return $this->flag_field_publish_date;
    }

    public function setFlagFieldPublishDate(bool $flag_field_publish_date): self
    {
        $this->flag_field_publish_date = $flag_field_publish_date;

        return $this;
    }

    public function getFlagActive(): ?bool
    {
        return $this->flag_active;
    }

    public function setFlagActive(bool $flag_active): self
    {
        $this->flag_active = $flag_active;

        return $this;
    }

    public function getCreateUser(): ?string
    {
        return $this->create_user;
    }

    public function setCreateUser(?string $create_user): self
    {
        $this->create_user = $create_user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdateUser(): ?string
    {
        return $this->update_user;
    }

    public function setUpdateUser(?string $update_user): self
    {
        $this->update_user = $update_user;

        return $this;
    }

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

    public function getFormVersion(): ?PublicationFormVersion
    {
        return $this->form_version;
    }

    public function setFormVersion(?PublicationFormVersion $form_version): self
    {
        $this->form_version = $form_version;

        return $this;
    }

    public function getFormParent(): ?self
    {
        return $this->form_parent;
    }

    public function setFormParent(?self $form_parent): static
    {
        $this->form_parent = $form_parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFormChildren(): Collection
    {
        return $this->form_children;
    }

    public function addFormChild(self $formChild): static
    {
        if (!$this->form_children->contains($formChild)) {
            $this->form_children->add($formChild);
            $formChild->setFormParent($this);
        }

        return $this;
    }

    public function removeFormChild(self $formChild): static
    {
        if ($this->form_children->removeElement($formChild)) {
            // set the owning side to null (unless already changed)
            if ($formChild->getFormParent() === $this) {
                $formChild->setFormParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PublicationMeta>
     */
    public function getPublicationMeta(): Collection
    {
        return $this->publicationMeta;
    }

    public function addPublicationMeta(PublicationMeta $publicationMeta): static
    {
        if (!$this->publicationMeta->contains($publicationMeta)) {
            $this->publicationMeta->add($publicationMeta);
            $publicationMeta->setForm($this);
        }

        return $this;
    }

    public function removePublicationMeta(PublicationMeta $publicationMeta): static
    {
        if (
            $this->publicationMeta->removeElement($publicationMeta) &&
            $publicationMeta->getForm() === $this
        ) {
            $publicationMeta->setForm(null);
        }

        return $this;
    }

}