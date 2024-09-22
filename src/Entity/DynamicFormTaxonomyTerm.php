<?php

namespace App\Entity;

use App\Repository\DynamicFormTaxonomyTermRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DynamicFormTaxonomyTermRepository::class)]
class DynamicFormTaxonomyTerm
{
    #[
        ORM\Id,
        ORM\Column(type: 'bigint', options: ["unsigned" => true], nullable: false),
        ORM\GeneratedValue()
    ]
    private ?string $id;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $dynamic_form_taxonomy_term;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $dynamic_form_taxonomy_term_code;

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

    #[ORM\ManyToOne(inversedBy: 'dynamicFormTaxonomyTerms')]
    private ?DynamicFormTaxonomy $dynamicFormTaxonomy = null;

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

    public function getDynamicFormTaxonomyTerm(): ?string
    {
        return $this->dynamic_form_taxonomy_term;
    }

    public function setDynamicFormTaxonomyTerm(string $dynamic_form_taxonomy_term): static
    {
        $this->dynamic_form_taxonomy_term = $dynamic_form_taxonomy_term;

        return $this;
    }

    public function getDynamicFormTaxonomyTermCode(): ?string
    {
        return $this->dynamic_form_taxonomy_term_code;
    }

    public function setDynamicFormTaxonomyTermCode(string $dynamic_form_taxonomy_term_code): static
    {
        $this->dynamic_form_taxonomy_term_code = $dynamic_form_taxonomy_term_code;

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

    public function getDynamicFormTaxonomy(): ?DynamicFormTaxonomy
    {
        return $this->dynamicFormTaxonomy;
    }

    public function setDynamicFormTaxonomy(?DynamicFormTaxonomy $dynamicFormTaxonomy): static
    {
        $this->dynamicFormTaxonomy = $dynamicFormTaxonomy;

        return $this;
    }

}
