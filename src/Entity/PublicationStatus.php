<?php

namespace App\Entity;

use App\Repository\PublicationStatusRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[
    ORM\Entity(repositoryClass: PublicationStatusRepository::class),ORM\HasLifecycleCallbacks,
    ORM\Table(name: "publication_status")
]
class PublicationStatus
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: 'bigint', options: ["unsigned" => true])
    ]
    #[Ignore]
    protected string $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $publication_status_name;

    #[ORM\Column(type: 'string', length: 50)]
    protected string $publication_status_code;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Ignore]
    protected $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    protected string $create_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    protected $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    protected string $update_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    protected $updated_at;

    #[ORM\Column(type: 'guid')]
    protected string $uuid;

    #[ORM\OneToMany(
        mappedBy: 'publication_status',
        targetEntity: Publication::class,
        fetch: 'EAGER',
        cascade: ["ALL"]
    )]
    #[Ignore]
    protected Collection $publications;

    public function __construct()
    {
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

    public function getPublicationStatusName(): ?string
    {
        return $this->publication_status_name;
    }

    public function setPublicationStatusName(string $publication_status_name): self
    {
        $this->publication_status_name = $publication_status_name;

        return $this;
    }

    public function getPublicationStatusCode(): ?string
    {
        return $this->publication_status_code;
    }

    public function setPublicationStatusCode(string $publication_status_code): self
    {
        $this->publication_status_code = $publication_status_code;

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
            $publication->setPublicationStatus($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if (
            $this->publications->removeElement($publication) &&
            $publication->getPublicationStatus() === $this
        ) {
            $publication->setPublicationStatus(null);
        }

        return $this;
    }

}