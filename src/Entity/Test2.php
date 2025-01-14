<?php

namespace App\Entity;

use App\Repository\Test2Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Test2Repository::class)]
class Test2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, test>
     */
    #[ORM\OneToMany(targetEntity: test::class, mappedBy: 'test2', orphanRemoval: true)]
    private Collection $name;

    public function __construct()
    {
        $this->name = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, test>
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(test $name): static
    {
        if (!$this->name->contains($name)) {
            $this->name->add($name);
            $name->setTest2($this);
        }

        return $this;
    }

    public function removeName(test $name): static
    {
        if ($this->name->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getTest2() === $this) {
                $name->setTest2(null);
            }
        }

        return $this;
    }
}
