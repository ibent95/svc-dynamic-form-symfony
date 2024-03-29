<?php

namespace App\Entity;

use App\Repository\PublicationGeneralTypeRepository;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[
    ORM\Entity(repositoryClass: PublicationGeneralTypeRepository::class),
    ORM\HasLifecycleCallbacks,
    ORM\Table(name: "publication_general_type"),
    ApiResource
]
class PublicationGeneralType
{
    #[
        ORM\Id,
        ORM\GeneratedValue(strategy: "IDENTITY"),
        ORM\Column(type: 'bigint', options: ["unsigned" => true])
    ]
    #[Ignore]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Ignore]
    private $publication_general_type_name;

    #[ORM\Column(type: 'string', length: 100)]
    #[Ignore]
    private $publication_general_type_code;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Ignore]
    private $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    private $create_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    private $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    private $update_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    private $updated_at;

    #[ORM\Column(type: 'guid')]
    private $uuid;

    #[ORM\OneToMany(
        mappedBy: 'publication_general_type',
        targetEntity: PublicationType::class,
        fetch: 'EAGER',
        cascade: ['ALL']
    )]
    #[Ignore]
    private $publication_types;

    #[ORM\OneToMany(
        mappedBy: 'publication_general_type',
        targetEntity: Publication::class,
        fetch: 'EAGER',
        cascade: ['ALL']
    )]
    #[Ignore]
    private $publications;

    public function __construct()
    {
        $this->publication_types = new ArrayCollection();
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

    public function getPublicationGeneralTypeName(): ?string
    {
        return $this->publication_general_type_name;
    }

    public function setPublicationGeneralTypeName(string $publication_general_type_name): self
    {
        $this->publication_general_type_name = $publication_general_type_name;

        return $this;
    }

    public function getPublicationGeneralTypeCode(): ?string
    {
        return $this->publication_general_type_code;
    }

    public function setPublicationGeneralTypeCode(string $publication_general_type_code): self
    {
        $this->publication_general_type_code = $publication_general_type_code;

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

    /**
     * @return Collection<int, PublicationType>
     */
    #[Ignore]
    public function getPublicationTypes(): Collection
    {
        return $this->publication_types;
    }

    public function addPublicationType(PublicationType $publicationType): self
    {
        if (!$this->publication_types->contains($publicationType)) {
            $this->publication_types[] = $publicationType;
            $publicationType->setPublicationGeneralType($this);
        }

        return $this;
    }

    public function removePublicationType(PublicationType $publicationType): self
    {
        if (
            $this->publication_types->removeElement($publicationType) &&
            $publicationType->getPublicationGeneralType() === $this
        ) {
            $publicationType->setPublicationGeneralType(null);
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
            $this->publications[] = $publication;
            $publication->setPublicationGeneralType($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if (
            $this->publications->removeElement($publication) &&
            $publication->getPublicationGeneralType() === $this
        ) {
            $publication->setPublicationGeneralType(null);
        }

        return $this;
    }

}