<?php

namespace App\Entity;

use App\Repository\PublicationTypeRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PublicationTypeRepository::class)]
#[ORM\Table(name: "publication_type")]
class PublicationType
{
    #[ORM\Id, ORM\GeneratedValue(strategy: "IDENTITY"), ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    #[Ignore]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Ignore]
    private $publication_type_name;

    #[ORM\Column(type: 'string', length: 50)]
    #[Ignore]
    private $publication_type_code;

    #[ORM\Column(type: 'bigint')]
    #[Ignore]
    private $id_publication_general_type;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Ignore]
    private $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    private $created_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    private $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    private $updated_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    private $updated_at;

    #[ORM\Column(type: 'guid')]
    private $uuid;

    #[ORM\ManyToOne(targetEntity: PublicationGeneralType::class, inversedBy: 'publication_types', fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'id_publication_general_type', referencedColumnName: 'id')]
    #[Ignore]
    private PublicationGeneralType $publication_general_type;

    #[ORM\OneToMany(mappedBy: 'publication_types', targetEntity: PublicationFormVersion::class, fetch: 'EAGER')]
    #[Ignore]
    private $publication_form_versions;

    #[ORM\OneToMany(mappedBy: 'publication_type', targetEntity: Publication::class, fetch: 'EAGER')]
    #[Ignore]
    private $publications;

    public function __construct()
    {
        $this->publication_form_versions = new ArrayCollection();
        $this->publications = new ArrayCollection();
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
     * @return Collection<int, PublicationFormVersion>
     */
    #[Ignore]
    public function getFormVersion(): Collection
    {
        return $this->publication_form_versions;
    }

    public function addFormVersion(PublicationFormVersion $formVersion): self
    {
        if (!$this->publication_form_versions->contains($formVersion)) {
            $this->publication_form_versions[] = $formVersion;
            $formVersion->setPublicationType($this);
        }

        return $this;
    }

    public function removeFormVersion(PublicationFormVersion $formVersion): self
    {
        if ($this->publication_form_versions->removeElement($formVersion)) {
            // set the owning side to null (unless already changed)
            if ($formVersion->getPublicationType() === $this) {
                $formVersion->setPublicationType(null);
            }
        }

        return $this;
    }

    public function isFlagActive(): ?bool
    {
        return $this->flag_active;
    }

}