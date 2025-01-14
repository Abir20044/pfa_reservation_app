<?php

namespace App\Service;

use App\Entity\Reservation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class NotificationService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private UrlGeneratorInterface $urlGenerator,
        private string $adminEmail
    ) {}

    public function sendReservationConfirmation(Reservation $reservation): void
    {
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($reservation->getClient()->getEmail())
            ->subject('Confirmation de votre réservation')
            ->html(
                $this->twig->render('emails/reservation_confirmation.html.twig', [
                    'reservation' => $reservation,
                    'url' => $this->urlGenerator->generate('app_reservation_show', 
                        ['id' => $reservation->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ])
            );

        $this->mailer->send($email);
    }

    public function sendReservationReminder(Reservation $reservation): void
    {
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($reservation->getClient()->getEmail())
            ->subject('Rappel de votre rendez-vous')
            ->html(
                $this->twig->render('emails/reservation_reminder.html.twig', [
                    'reservation' => $reservation,
                    'url' => $this->urlGenerator->generate('app_reservation_show', 
                        ['id' => $reservation->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ])
            );

        $this->mailer->send($email);
    }

    public function sendReservationCancellation(Reservation $reservation): void
    {
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($reservation->getClient()->getEmail())
            ->subject('Annulation de votre réservation')
            ->html(
                $this->twig->render('emails/reservation_cancellation.html.twig', [
                    'reservation' => $reservation
                ])
            );

        $this->mailer->send($email);
    }
}
