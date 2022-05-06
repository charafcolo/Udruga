<?php

namespace App\Entity;


use App\Entity\Association;
use App\Entity\User;
use App\Repository\EventRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api_event")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups("api_event")
     */
    private $type;

    /**
     * @ORM\Column(type="text")
     * @Groups("api_event")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("api_event")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     * @Groups("api_event")
     */
    private $maxMember;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups("api_event")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups("api_event")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("api_event")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("api_event")
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="events")
     * @Groups("api_event")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Association::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("api_event")
     */
    private $association;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMaxMember(): ?int
    {
        return $this->maxMember;
    }

    public function setMaxMember(int $maxMember): self
    {
        $this->maxMember = $maxMember;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }
}
