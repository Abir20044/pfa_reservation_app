<?php

namespace App\Entity;

use App\Repository\TimeSlotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeSlotRepository::class)]
class TimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column]
    private ?bool $isAvailable = true;

    #[ORM\OneToOne(mappedBy: 'timeSlot', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): static
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;
        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        if ($reservation === null && $this->reservation !== null) {
            $this->reservation->setTimeSlot(null);
        }
        if ($reservation !== null && $reservation->getTimeSlot() !== $this) {
            $reservation->setTimeSlot($this);
        }
        $this->reservation = $reservation;
        return $this;
    }
}
