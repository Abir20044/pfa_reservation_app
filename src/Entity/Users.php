<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, Appointments>
     */
    #[ORM\OneToMany(targetEntity: Appointments::class, mappedBy: 'clientname')]
    private Collection $name;

    #[ORM\Column(length: 255)]
    private ?string $Password = null;

    public function __construct()
    {
        $this->name = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Appointments>
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(Appointments $name): static
    {
        if (!$this->name->contains($name)) {
            $this->name->add($name);
            $name->setClientname($this);
        }

        return $this;
    }

    public function removeName(Appointments $name): static
    {
        if ($this->name->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getClientname() === $this) {
                $name->setClientname(null);
            }
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }
}
