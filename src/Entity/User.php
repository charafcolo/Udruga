<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="users")
     */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity=Association::class, inversedBy="admin", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $association;

    /**
     * @ORM\ManyToOne(targetEntity=Association::class, inversedBy="members")
     */
    private $associationMember;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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
            $event->addUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeUser($this);
        }

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(Association $association): self
    {
        $this->association = $association;

        return $this;
    }

    public function getAssociationMember(): ?Association
    {
        return $this->associationMember;
    }

    public function setAssociationMember(?Association $associationMember): self
    {
        $this->associationMember = $associationMember;

        return $this;
    }
}
