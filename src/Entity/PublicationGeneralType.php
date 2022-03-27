<?php

namespace App\Entity;

use App\Repository\PublicationGeneralTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PublicationGeneralTypeRepository::class)]
class PublicationGeneralType
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'bigint', options: ["unsigned" => true])]
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
    #[Ignore]
    private $uuid;

    #[ORM\OneToMany(mappedBy: 'publicationGeneralType', targetEntity: PublicationType::class)]
    #[Ignore]
    private $publication_type;

    public function __construct()
    {
        $this->publication_type = new ArrayCollection();
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

    /**
     * @return Collection<int, PublicationType>
     */
    public function getPublicationType(): Collection
    {
        return $this->publication_type;
    }

    public function addPublicationType(PublicationType $publicationType): self
    {
        if (!$this->publication_type->contains($publicationType)) {
            $this->publication_type[] = $publicationType;
            $publicationType->setPublicationGeneralType($this);
        }

        return $this;
    }

    public function removePublicationType(PublicationType $publicationType): self
    {
        if ($this->publication_type->removeElement($publicationType)) {
            // set the owning side to null (unless already changed)
            if ($publicationType->getPublicationGeneralType() === $this) {
                $publicationType->setPublicationGeneralType(null);
            }
        }

        return $this;
    }

}