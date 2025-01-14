<?php

namespace App\Entity;

use App\Repository\AppointmentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentsRepository::class)]
class Appointments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Servicename = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'name')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $clientname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServicename(): ?string
    {
        return $this->Servicename;
    }

    public function setServicename(string $Servicename): static
    {
        $this->Servicename = $Servicename;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getClientname(): ?Users
    {
        return $this->clientname;
    }

    public function setClientname(?Users $clientname): static
    {
        $this->clientname = $clientname;

        return $this;
    }
}
