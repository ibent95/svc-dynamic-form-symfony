<?php

namespace App\Entity;

use App\Repository\PublicationFormVersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PublicationFormVersionRepository::class), ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "publication_form_version")]
class PublicationFormVersion
{
    #[ORM\Id, ORM\GeneratedValue(strategy: "IDENTITY"), ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    #[Ignore]
    protected $id;

    #[ORM\Column(type: 'bigint')]
    //#[Ignore]
    private $publication_type_id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Ignore]
    protected $publication_form_version_name;

    #[ORM\Column(type: 'string', length: 50)]
    #[Ignore]
    protected $publication_form_version_code;

    #[ORM\Column(type: 'json', nullable: true, options: ['default' => '{"type":"no_grid_system","cols":12,"config":{}}'])] // '{"type":"no_grid_system","cols":12,"config":{}}'
    private $grid_system = [];

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Ignore]
    protected $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    protected $created_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    protected $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Ignore]
    protected $updated_user;

    #[ORM\Column(type: 'datetime')]
    #[Ignore]
    protected $updated_at;

    #[ORM\Column(type: 'guid')]
    protected $uuid;

    #[ORM\ManyToOne(targetEntity: PublicationType::class, inversedBy: 'form_version')]
    #[Ignore]
    protected $publicationType;

    #[ORM\OneToMany(mappedBy: 'publicationFormVersion', targetEntity: PublicationForm::class)]
    #[Ignore]
    protected $publicationForms;

    #[ORM\OneToMany(mappedBy: 'publicationFormVersion', targetEntity: Publication::class)]
    #[Ignore]
    private $publications;

    public function __construct()
    {
        $this->publicationForms = new ArrayCollection();
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

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPublicationTypeId(): ?string
    {
        return $this->publication_type_id;
    }

    public function setPublicationTypeId(string $publication_type_id): self
    {
        $this->publication_type_id = $publication_type_id;

        return $this;
    }

    public function getPublicationFormVersionName(): ?string
    {
        return $this->publication_form_version_name;
    }

    public function setPublicationFormVersionName(string $publication_form_version_name): self
    {
        $this->publication_form_version_name = $publication_form_version_name;

        return $this;
    }

    public function getPublicationFormVersionCode(): ?string
    {
        return $this->publication_form_version_code;
    }

    public function setPublicationFormVersionCode(string $publication_form_version_code): self
    {
        $this->publication_form_version_code = $publication_form_version_code;

        return $this;
    }

    public function getGridSystem(): array
    {
        return $this->grid_system;
    }

    public function setGridSystem(?array $grid_system): self
    {
        $this->grid_system = $grid_system;

        return $this;
    }

    public function isFlagActive(): ?bool
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

    public function getPublicationType(): ?PublicationType
    {
        return $this->publicationType;
    }

    public function setPublicationType(?PublicationType $publicationType): self
    {
        $this->publicationType = $publicationType;

        return $this;
    }

    /**
     * @return Collection<int, PublicationForm>
     */
    public function getPublicationForms(): Collection
    {
        return $this->publicationForms;
    }

    public function addPublicationForm(PublicationForm $publicationForm): self
    {
        if (!$this->publicationForms->contains($publicationForm)) {
            $this->publicationForms->add($publicationForm);
            $publicationForm->setPublicationFormVersion($this);
        }

        return $this;
    }

    public function removePublicationForm(PublicationForm $publicationForm): self
    {
        if ($this->publicationForms->removeElement($publicationForm)) {
            // set the owning side to null (unless already changed)
            if ($publicationForm->getPublicationFormVersion() === $this) {
                $publicationForm->setPublicationFormVersion(null);
            }
        }

        return $this;
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
            $this->publications->add($publication);
            $publication->setPublicationFormVersion($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getPublicationFormVersion() === $this) {
                $publication->setPublicationFormVersion(null);
            }
        }

        return $this;
    }

}