<?php

namespace App\Entity;

use App\Repository\PublicationTypeRepository;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\Groups;

#[
    ORM\Entity(repositoryClass: PublicationTypeRepository::class),
    ORM\HasLifecycleCallbacks,
    ORM\Table(name: "publication_type"),
    ApiResource
]
class PublicationType
{
    #[
        ORM\Id,
        ORM\GeneratedValue(strategy: "IDENTITY"),
        ORM\Column(type: 'bigint', options: ["unsigned" => true])
    ]
    private $id;

    #[ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    private $id_publication_general_type;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['public', 'internal'])]
    private $publication_type_name;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['public', 'internal'])]
    private $publication_type_code;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Groups(['internal'])]
    private $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups(['internal'])]
    private $create_user;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['internal'])]
    private $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups(['internal'])]
    private $update_user;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['internal'])]
    private $updated_at;

    #[ORM\Column(type: 'guid')]
    #[Groups(['public', 'internal'])]
    private $uuid;

    #[
        ORM\ManyToOne(
            targetEntity: PublicationGeneralType::class,
            inversedBy: 'publication_types',
            fetch: 'LAZY'
        ),
        ORM\JoinColumn(
            name: 'id_publication_general_type',
            referencedColumnName: 'id',
            onDelete:"CASCADE"
        )
    ]
    #[Groups(['public', 'internal'])]
    private $publication_general_type;

    #[ORM\OneToMany(
        mappedBy: 'publication_type',
        targetEntity: PublicationFormVersion::class,
        fetch: 'LAZY',
        cascade: ["ALL"]
    )]
    #[Groups(['publication_type-form_versions'])]
    private $form_versions;

    #[ORM\OneToMany(mappedBy: 'publication_type', targetEntity: Publication::class, fetch: 'LAZY', cascade: ["ALL"])]
    #[Groups(['publication_type-publications'])]
    private $publications;

    public function __construct()
    {
        $this->form_versions = new ArrayCollection();
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

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setPublicationType($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getPublicationType() === $this) {
                $publication->setPublicationType(null);
            }
        }

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdPublicationGeneralType(): ?string
    {
        return $this->id_publication_general_type;
    }

    public function setIdPublicationGeneralType(string $id_publication_general_type): self
    {
        $this->id_publication_general_type = $id_publication_general_type;

        return $this;
    }

    public function getPublicationTypeName(): ?string
    {
        return $this->publication_type_name;
    }

    public function setPublicationTypeName(string $publication_type_name): self
    {
        $this->publication_type_name = $publication_type_name;

        return $this;
    }

    public function getPublicationTypeCode(): ?string
    {
        return $this->publication_type_code;
    }

    public function setPublicationTypeCode(string $publication_type_code): self
    {
        $this->publication_type_code = $publication_type_code;

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

    public function getPublicationGeneralType(): ?PublicationGeneralType
    {
        return $this->publication_general_type;
    }

    public function setPublicationGeneralType(?PublicationGeneralType $publication_general_type): self
    {
        $this->publication_general_type = $publication_general_type;

        return $this;
    }

    /**
     * @return Collection<int, FormVersion>
     */
    public function getFormVersions(): Collection
    {
        return $this->form_versions;
    }

    public function addFormVersion(PublicationFormVersion $formVersion): self
    {
        if (!$this->form_versions->contains($formVersion)) {
            $this->form_versions[] = $formVersion;
            $formVersion->setPublicationType($this);
        }

        return $this;
    }

    public function removeFormVersion(PublicationFormVersion $formVersion): self
    {
        if ($this->form_versions->removeElement($formVersion)) {
            // set the owning side to null (unless already changed)
            if ($formVersion->getPublicationType() === $this) {
                $formVersion->setPublicationType(null);
            }
        }

        return $this;
    }

}