<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Publication
{
    #[ORM\Id, ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    #[Ignore]
    private $id;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private $title;

    #[ORM\Column(type: 'bigint', options: ["unsigned" => true], nullable: true)]
    #[Ignore]
    private $id_publication_general_type;

    #[ORM\Column(type: 'bigint', options: ["unsigned" => true], nullable: true)]
    #[Ignore]
    private $id_publication_type;

    #[ORM\Column(type: 'bigint', options: ["unsigned" => true], nullable: true)]
    #[Ignore]
    private $id_publication_form_version;

    #[ORM\Column(type: 'bigint', options: ["unsigned" => true], nullable: true)]
    #[Ignore]
    private $id_publication_status;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $publication_date;

    #[ORM\Column(type:'boolean', options: ['default' => true])]
    #[Ignore]
    private $flag_active;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => 'system'], nullable: true)]
    #[Ignore]
    private $create_user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Ignore]
    private $created_at;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => 'system'], nullable: true)]
    #[Ignore]
    private $update_user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Ignore]
    private $updated_at;

    #[ORM\Column(type: 'guid', nullable: false)]
    private $uuid;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: PublicationMeta::class, fetch: 'EAGER', cascade: ["persist"])]
    #[Ignore]
    private $publication_metas;

    #[ORM\ManyToOne(targetEntity: PublicationGeneralType::class, inversedBy: 'publications', fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'id_publication_general_type', referencedColumnName: 'id')]
    #[Ignore]
    private $publication_general_type;

    #[ORM\ManyToOne(targetEntity: PublicationType::class, inversedBy: 'publications', fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'id_publication_type', referencedColumnName: 'id')]
    #[Ignore]
    private $publication_type;

    #[ORM\ManyToOne(targetEntity: PublicationFormVersion::class, inversedBy: 'publications', fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'id_publication_form_version', referencedColumnName: 'id')]
    #[Ignore]
    private $publication_form_version;

    #[ORM\ManyToOne(targetEntity: PublicationStatus::class, inversedBy: 'publications', fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'id_publication_status', referencedColumnName: 'id')]
    #[Ignore]
    private $publication_status;

    public function __construct()
    {
        $this->publication_metas = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->flag_active = true;
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
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

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
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

    #[Ignore]
    public function getIdPublicationGeneralType(): ?string
    {
        return $this->id_publication_general_type;
    }

    public function setIdPublicationGeneralType(string $id_publication_general_type): self
    {
        $this->id_publication_general_type = $id_publication_general_type;

        return $this;
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

    #[Ignore]
    public function getIdPublicationFormVersion(): ?string
    {
        return $this->id_publication_form_version;
    }

    public function setIdPublicationFormVersion(string $id_publication_form_version): self
    {
        $this->id_publication_form_version = $id_publication_form_version;

        return $this;
    }

    #[Ignore]
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
     * @return Collection<int, PublicationMeta>
     */
    #[Ignore]
    public function getPublicationMetas(): Collection
    {
        return $this->publication_metas;
    }

    public function addPublicationMetas(PublicationMeta $publicationMetas): self
    {
        if (!$this->publication_metas->contains($publicationMetas)) {
            $this->publication_metas[] = $publicationMetas;
            $publicationMetas->setPublication($this);
        }

        return $this;
    }

    public function removePublicationMetas(PublicationMeta $publicationMetas): self
    {
        if ($this->publication_metas->removeElement($publicationMetas)) {
            // set the owning side to null (unless already changed)
            if ($publicationMetas->getPublication() === $this) {
                $publicationMetas->setPublication(null);
            }
        }

        return $this;
    }

    #[Ignore]
    public function getPublicationGeneralType(): ?PublicationGeneralType
    {
        return $this->publication_general_type;
    }

    public function setPublicationGeneralType(?PublicationGeneralType $publication_general_type): self
    {
        $this->publication_general_type = $publication_general_type;

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

    #[Ignore]
    public function getPublicationFormVersion(): ?PublicationFormVersion
    {
        return $this->publication_form_version;
    }

    public function setPublicationFormVersion(?PublicationFormVersion $publication_form_version): self
    {
        $this->publication_form_version = $publication_form_version;

        return $this;
    }

    #[Ignore]
    public function getPublicationStatus(): ?PublicationStatus
    {
        return $this->publication_status;
    }

    public function setPublicationStatus(?PublicationStatus $publication_status): self
    {
        $this->publication_status = $publication_status;

        return $this;
    }

    public function isFlagActive(): ?bool
    {
        return $this->flag_active;
    }

}