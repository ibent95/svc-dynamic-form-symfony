<?php

namespace App\Entity;

use App\Repository\PublicationFormVersionRepository;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[
    ORM\Entity(repositoryClass: PublicationFormVersionRepository::class),
    ORM\HasLifecycleCallbacks,
    ORM\Table(name: "publication_form_version"),
    ApiResource
]
class PublicationFormVersion
{
    #[
        ORM\Id, ORM\GeneratedValue(strategy: "IDENTITY"),
        ORM\Column(type: 'bigint', options: ["unsigned" => true])
    ]
    #[Ignore]
    protected $id;

    #[ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    #[Ignore]
    private $id_publication_type;

    #[ORM\Column(type: 'string', length: 255)]
    protected $publication_form_version_name;

    #[ORM\Column(type: 'string', length: 50)]
    protected $publication_form_version_code;

    #[ORM\Column(
        type: 'json', nullable: true,
        options: ['default' => '{"type":"no_grid_system","cols":12,"config":{}}']
    )]
    private $grid_system = [];

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Ignore]
    protected $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    protected $create_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    protected $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    protected $update_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    protected $updated_at;

    #[ORM\Column(type: 'guid')]
    protected $uuid;

    #[ORM\ManyToOne(
        targetEntity: PublicationType::class,
        cascade: ['ALL'],
        fetch: 'EAGER',
        inversedBy: 'form_versions'
    )]
    #[ORM\JoinColumn(name: 'id_publication_type', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Ignore]
    protected $publication_type;

    #[ORM\OneToMany(
        mappedBy: 'form_version',
        targetEntity: PublicationMeta::class,
        fetch: 'EAGER',
        cascade: ['ALL']
    )]
    #[Ignore]
    protected $publication_metas;

    #[
        ORM\OneToMany(
            mappedBy: 'form_version',
            targetEntity: PublicationForm::class,
            fetch: 'EAGER',
            cascade: ['ALL']
        ),
        ORM\OrderBy(['order_position' => 'ASC'])
    ]
    #[Ignore]
    protected $forms;

    #[ORM\OneToMany(
        mappedBy: 'publication_form_version',
        targetEntity: Publication::class,
        fetch: 'EAGER',
        cascade: ["ALL"]
    )]
    #[Ignore]
    private $publications;

    public function __construct()
    {
        $this->publication_metas = new ArrayCollection();
        $this->forms = new ArrayCollection();
        $this->publications = new ArrayCollection();
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

    #[Ignore]
    public function getIdPublicationType(): ?string
    {
        return $this->id_publication_type;
    }

    public function setIdPublicationType(string $id_publication_type): self
    {
        $this->id_publication_type = $id_publication_type;

        return $this;
    }

    public function getPublicationFormVersionName(): ?string
    {
        return $this->publication_form_version_name;
    }

    public function setPublicationFormVersionName(string $publication_form_version_name): self
    {
        $this->publication_form_version_name = $publication_form_version_name;

        return $this;
    }

    public function getPublicationFormVersionCode(): ?string
    {
        return $this->publication_form_version_code;
    }

    public function setPublicationFormVersionCode(string $publication_form_version_code): self
    {
        $this->publication_form_version_code = $publication_form_version_code;

        return $this;
    }

    public function getGridSystem(): array
    {
        return $this->grid_system;
    }

    public function setGridSystem(?array $grid_system): self
    {
        $this->grid_system = $grid_system;

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

    #[Ignore]
    public function getCreateUser(): ?string
    {
        return $this->create_user;
    }

    public function setCreateUser(?string $create_user): self
    {
        $this->create_user = $create_user;

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
    public function getUpdateUser(): ?string
    {
        return $this->update_user;
    }

    public function setUpdateUser(?string $update_user): self
    {
        $this->update_user = $update_user;

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

    #[Ignore]
    public function getPublicationType(): ?PublicationType
    {
        return $this->publication_type;
    }

    public function setPublicationType(?PublicationType $publication_type): self
    {
        $this->publication_type = $publication_type;

        return $this;
    }

    /**
     * @return Collection<int, PublicationForm>
     */
    #[Ignore]
    public function getForms(): Collection
    {
        return $this->forms;
    }

    public function addForm(PublicationForm $publicationForm): self
    {
        if (!$this->forms->contains($publicationForm)) {
            $this->forms->add($publicationForm);
            $publicationForm->setFormVersion($this);
        }

        return $this;
    }

    public function removeForm(PublicationForm $publicationForm): self
    {
        if (
            $this->forms->removeElement($publicationForm) && 
            $publicationForm->getFormVersion() === $this
        ) {
            $publicationForm->setFormVersion(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    #[Ignore]
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setPublicationFormVersion($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if (
            $this->publications->removeElement($publication) &&
            $publication->getPublicationFormVersion() === $this
        ) {
            $publication->setPublicationFormVersion(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    #[Ignore]
    public function getPublicationMetas(): Collection
    {
        return $this->publication_metas;
    }

}