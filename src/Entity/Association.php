<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\User;
use App\Entity\Event;
use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssociationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Association
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("api_association")
     * @Groups("api_user")
     * @Groups("api_asso")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups("api_association")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups("api_association")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=9)
     * @Groups("api_association")
     */
    private $siren;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups("api_association")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="association", orphanRemoval=true)
     * @Groups("api_association")
     */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="association", cascade={"persist", "remove"})
     * @Groups("api_association")
     */
    private $admin;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="associationMember")
     * @Groups("api_association")
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
            // set the owning side to null (unless  already changed)
            if ($member->getAssociationMember() === $this) {
                $member->setAssociationMember(null);
            }
        }

        return $this;
    }
}
