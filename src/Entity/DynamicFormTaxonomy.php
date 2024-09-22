<?php

namespace App\Entity;

use App\Repository\DynamicFormTaxonomyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DynamicFormTaxonomyRepository::class)]
class DynamicFormTaxonomy
{
    #[
        ORM\Id,
        ORM\Column(type: 'bigint', options: ["unsigned" => true], nullable: false),
        ORM\GeneratedValue()
    ]
    private ?string $id;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $dynamic_form_taxonomy;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $dynamic_form_taxonomy_code;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: false)]
    private ?bool $flag_active;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $create_user;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $update_user;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $updated_at;

    #[ORM\Column(type: Types::GUID, nullable: false)]
    private ?string $uuid;

    #[ORM\OneToMany(mappedBy: 'dynamicFormTaxonomy', targetEntity: DynamicFormTaxonomyTerm::class)]
    private Collection $dynamicFormTaxonomyTerms;

    public function __construct()
    {
        $this->dynamicFormTaxonomyTerms = new ArrayCollection();
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

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDynamicFormTaxonomy(): ?string
    {
        return $this->dynamic_form_taxonomy;
    }

    public function setDynamicFormTaxonomy(string $dynamic_form_taxonomy): static
    {
        $this->dynamic_form_taxonomy = $dynamic_form_taxonomy;

        return $this;
    }

    public function getDynamicFormTaxonomyCode(): ?string
    {
        return $this->dynamic_form_taxonomy_code;
    }

    public function setDynamicFormTaxonomyCode(string $dynamic_form_taxonomy_code): static
    {
        $this->dynamic_form_taxonomy_code = $dynamic_form_taxonomy_code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isFlagActive(): ?bool
    {
        return $this->flag_active;
    }

    public function setFlagActive(bool $flag_active): static
    {
        $this->flag_active = $flag_active;

        return $this;
    }

    public function getCreateUser(): ?string
    {
        return $this->create_user;
    }

    public function setCreateUser(string $create_user): static
    {
        $this->create_user = $create_user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdateUser(): ?string
    {
        return $this->update_user;
    }

    public function setUpdateUser(string $update_user): static
    {
        $this->update_user = $update_user;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return Collection<int, DynamicFormTaxonomyTerm>
     */
    public function getDynamicFormTaxonomyTerms(): Collection
    {
        return $this->dynamicFormTaxonomyTerms;
    }

    public function addDynamicFormTaxonomyTerm(DynamicFormTaxonomyTerm $dynamicFormTaxonomyTerm): static
    {
        if (!$this->dynamicFormTaxonomyTerms->contains($dynamicFormTaxonomyTerm)) {
            $this->dynamicFormTaxonomyTerms->add($dynamicFormTaxonomyTerm);
            $dynamicFormTaxonomyTerm->setDynamicFormTaxonomy($this);
        }

        return $this;
    }

    public function removeDynamicFormTaxonomyTerm(DynamicFormTaxonomyTerm $dynamicFormTaxonomyTerm): static
    {
        if ($this->dynamicFormTaxonomyTerms->removeElement($dynamicFormTaxonomyTerm)) {
            // set the owning side to null (unless already changed)
            if ($dynamicFormTaxonomyTerm->getDynamicFormTaxonomy() === $this) {
                $dynamicFormTaxonomyTerm->setDynamicFormTaxonomy(null);
            }
        }

        return $this;
    }

}
