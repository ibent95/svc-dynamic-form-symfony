<?php

namespace App\Entity;

use App\Repository\PublicationTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationTypeRepository::class)]
class PublicationType
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $publication_type_name;

    #[ORM\Column(type: 'string', length: 50)]
    private $publication_type_code;

    #[ORM\Column(type: 'bigint')]
    private $id_publication_general_type;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $created_user;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $updated_user;

    #[ORM\Column(type: 'datetime')]
    private $updated_at;

    #[ORM\Column(type: 'guid')]
    private $uuid;

    #[ORM\ManyToOne(targetEntity: PublicationGeneralType::class, inversedBy: 'publication_type')]
    private $publicationGeneralType;

    #[ORM\OneToMany(mappedBy: 'publicationType', targetEntity: PublicationFormVersion::class)]
    private $form_version;

    public function __construct()
    {
        $this->form_version = new ArrayCollection();
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

    public function getIdPublicationGeneralType(): ?string
    {
        return $this->id_publication_general_type;
    }

    public function setIdPublicationGeneralType(string $id_publication_general_type): self
    {
        $this->id_publication_general_type = $id_publication_general_type;

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

    public function getPublicationGeneralType(): ?PublicationGeneralType
    {
        return $this->publicationGeneralType;
    }

    public function setPublicationGeneralType(?PublicationGeneralType $publicationGeneralType): self
    {
        $this->publicationGeneralType = $publicationGeneralType;

        return $this;
    }

    /**
     * @return Collection<int, PublicationFormVersion>
     */
    public function getFormVersion(): Collection
    {
        return $this->form_version;
    }

    public function addFormVersion(PublicationFormVersion $formVersion): self
    {
        if (!$this->form_version->contains($formVersion)) {
            $this->form_version[] = $formVersion;
            $formVersion->setPublicationType($this);
        }

        return $this;
    }

    public function removeFormVersion(PublicationFormVersion $formVersion): self
    {
        if ($this->form_version->removeElement($formVersion)) {
            // set the owning side to null (unless already changed)
            if ($formVersion->getPublicationType() === $this) {
                $formVersion->setPublicationType(null);
            }
        }

        return $this;
    }

}