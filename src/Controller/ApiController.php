<?php

namespace App\Controller;

use App\Repository\TimeSlotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/timeslots', name: 'timeslots', methods: ['GET'])]
    public function getTimeSlots(Request $request, TimeSlotRepository $timeSlotRepository): JsonResponse
    {
        $date = $request->query->get('date');
        $serviceId = $request->query->get('service');

        $timeSlots = $timeSlotRepository->findAvailableSlots(new \DateTime($date), $serviceId);

        $formattedSlots = array_map(function($slot) {
            return [
                'id' => $slot->getId(),
                'time' => $slot->getStartTime()->format('H:i'),
            ];
        }, $timeSlots);

        return $this->json($formattedSlots);
    }
}
