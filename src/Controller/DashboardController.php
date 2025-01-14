<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\TimeSlot;
use App\Repository\ServiceRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }
        return $this->redirectToRoute('app_user_dashboard');
    }

    #[Route('/dashboard/admin', name: 'app_admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDashboard(UserRepository $userRepository, ReservationRepository $reservationRepository): Response
    {
        return $this->render('dashboard/admin_dashboard.html.twig', [
            'users' => $userRepository->findAll(),
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/dashboard/user', name: 'app_user_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function userDashboard(ServiceRepository $serviceRepository, ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        return $this->render('dashboard/user_dashboard.html.twig', [
            'services' => $serviceRepository->findAll(),
            'reservations' => $reservationRepository->findBy(['client' => $user]),
        ]);
    }
}
