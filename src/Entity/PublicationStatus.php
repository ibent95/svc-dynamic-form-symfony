<?php

namespace App\Entity;

use App\Repository\PublicationStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationStatusRepository::class)]
class PublicationStatus
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $publication_status_name;

    #[ORM\Column(type: 'string', length: 50)]
    private $publication_status_code;

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

}