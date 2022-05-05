<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssociationRepository::class)
 */
class Association
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siren;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $registrationCode;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="association", orphanRemoval=true)
     */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="association", cascade={"persist", "remove"})
     */
    private $admin;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="associationMember")
     */
    private $members;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegistrationCode(): ?int
    {
        return $this->registrationCode;
    }

    public function setRegistrationCode(int $registrationCode): self
    {
        $this->registrationCode = $registrationCode;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAssociation($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAssociation() === $this) {
                $event->setAssociation(null);
            }
        }

        return $this;
    }

    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    public function setAdmin(User $admin): self
    {
        // set the owning side of the relation if necessary
        if ($admin->getAssociation() !== $this) {
            $admin->setAssociation($this);
        }

        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setAssociationMember($this);
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getAssociationMember() === $this) {
                $member->setAssociationMember(null);
            }
        }

        return $this;
    }
}
