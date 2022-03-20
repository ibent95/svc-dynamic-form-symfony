<?php

namespace App\Entity;

use App\Repository\PublicationFormVersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PublicationFormVersionRepository::class), ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "publication_form_version")]
class PublicationFormVersion
{
    #[ORM\Id, ORM\GeneratedValue(strategy: "IDENTITY"), ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    protected $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected $publication_form_version_name;

    #[ORM\Column(type: 'string', length: 50)]
    protected $publication_form_version_code;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    protected $flag_active;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    protected $created_user;

    #[ORM\Column(type: 'datetime')]
    protected $created_at;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    protected $updated_user;

    #[ORM\Column(type: 'datetime')]
    protected $updated_at;

    #[ORM\Column(type: 'guid')]
    protected $uuid;

    #[ORM\ManyToOne(targetEntity: PublicationType::class, inversedBy: 'form_version')]
    #[Ignore]
    protected $publicationType;

    #[ORM\OneToMany(mappedBy: 'publicationFormVersion', targetEntity: Form::class)]
    #[Ignore]
    protected $form;

    public function __construct()
    {
        $this->form = new ArrayCollection();
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
     * @return Collection<int, Form>
     */
    public function getForm(): Collection
    {
        return $this->form;
    }

    public function addForm(Form $form): self
    {
        if (!$this->form->contains($form)) {
            $this->form[] = $form;
            $form->setPublicationFormVersion($this);
        }

        return $this;
    }

    public function removeForm(Form $form): self
    {
        if ($this->form->removeElement($form)) {
            // set the owning side to null (unless already changed)
            if ($form->getPublicationFormVersion() === $this) {
                $form->setPublicationFormVersion(null);
            }
        }

        return $this;
    }

}