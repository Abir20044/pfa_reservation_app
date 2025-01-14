<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'name')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Test2 $test2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest2(): ?Test2
    {
        return $this->test2;
    }

    public function setTest2(?Test2 $test2): static
    {
        $this->test2 = $test2;

        return $this;
    }
}
