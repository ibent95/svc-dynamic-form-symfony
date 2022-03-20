<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    private $id;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private $title;

    #[ORM\Column(type: 'bigint')]
    private $id_publication_general_type;

    #[ORM\Column(type: 'bigint')]
    private $id_publication_type;

    #[ORM\Column(type: 'bigint')]
    private $id_publication_form_version;

    #[ORM\Column(type: 'bigint')]
    private $id_publication_status;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $publication_date;

    #[ORM\Column(type:'boolean', options: ['default' => true])]
    private $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $created_user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $updated_user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private $updated_at;

    #[ORM\Column(type: 'guid', nullable: false)]
    private $uuid;

    #[ORM\ManyToOne(targetEntity: PublicationGeneralType::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private $publication_general_type;

    #[ORM\ManyToOne(targetEntity: PublicationType::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private $publication_type;

    #[ORM\ManyToOne(targetEntity: PublicationFormVersion::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private $publication_form_version;

    #[ORM\ManyToOne(targetEntity: PublicationStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private $publication_status;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: PublicationMeta::class)]
    #[Ignore]
    private $publication_meta;

    public function __construct()
    {
        $this->publication_meta = new ArrayCollection();
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getIdPublicationType(): ?string
    {
        return $this->id_publication_type;
    }

    public function setIdPublicationType(string $id_publication_type): self
    {
        $this->id_publication_type = $id_publication_type;

        return $this;
    }

    public function getIdPublicationFormVersion(): ?string
    {
        return $this->id_publication_form_version;
    }

    public function setIdPublicationFormVersion(string $id_publication_form_version): self
    {
        $this->id_publication_form_version = $id_publication_form_version;

        return $this;
    }

    public function getIdPublicationStatus(): ?string
    {
        return $this->id_publication_status;
    }

    public function setIdPublicationStatus(string $id_publication_status): self
    {
        $this->id_publication_status = $id_publication_status;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function setPublicationDate(?\DateTimeInterface $publication_date): self
    {
        $this->publication_date = $publication_date;

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

    public function getCreatedUser(): ?string
    {
        return $this->created_user;
    }

    public function setCreatedUser(?string $created_user): self
    {
        $this->created_user = $created_user;

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

    public function getUpdatedUser(): ?string
    {
        return $this->updated_user;
    }

    public function setUpdatedUser(?string $updated_user): self
    {
        $this->updated_user = $updated_user;

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

    public function getPublicationType(): ?PublicationType
    {
        return $this->publication_type;
    }

    public function setPublicationType(?PublicationType $publication_type): self
    {
        $this->publication_type = $publication_type;

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

    public function getPublicationStatus(): ?PublicationStatus
    {
        return $this->publication_status;
    }

    public function setPublicationStatus(?PublicationStatus $publication_status): self
    {
        $this->publication_status = $publication_status;

        return $this;
    }

    /**
     * @return Collection<int, PublicationMeta>
     */
    public function getPublicationMeta(): Collection
    {
        return $this->publication_meta;
    }

    public function addPublicationMetum(PublicationMeta $publicationMetum): self
    {
        if (!$this->publication_meta->contains($publicationMetum)) {
            $this->publication_meta[] = $publicationMetum;
            $publicationMetum->setPublication($this);
        }

        return $this;
    }

    public function removePublicationMetum(PublicationMeta $publicationMetum): self
    {
        if ($this->publication_meta->removeElement($publicationMetum)) {
            // set the owning side to null (unless already changed)
            if ($publicationMetum->getPublication() === $this) {
                $publicationMetum->setPublication(null);
            }
        }

        return $this;
    }

}