<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\WorkTimeService;

class WorkTimeController extends AbstractController
{
    private WorkTimeService $workTimeService;

    public function __construct(WorkTimeService $workTimeService)
    {
        $this->workTimeService = $workTimeService;
    }

    #[Route('/api/employee/{id}/worktime', name: 'add_worktime', methods: ['POST'])]
    public function addWorkTime(Request $request, int $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data)
                throw new BadRequestHttpException('Nieprawidłowe dane!');
            $data['id'] = $id;
            $this->workTimeService->addWorkTime($data);

            return $this->json([
                "response" => [
                    "Czas pracy został dodany!"
                ]
            ], Response::HTTP_CREATED);
        } catch (BadRequestHttpException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/employee/{id}/worktime', name: 'show_worktime', methods: ['GET'])]
    public function showWorkTime(Request $request, int $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data)
                throw new BadRequestHttpException('Nieprawidłowe dane!');
            $data['id'] = $id;
            $workTime = $this->workTimeService->showWorkTime($data);

            return $this->json([
                "response" => [
                    $workTime
                ]
            ], Response::HTTP_OK);
        } catch (BadRequestHttpException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
