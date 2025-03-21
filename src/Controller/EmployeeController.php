<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\EmployeeService;

class EmployeeController extends AbstractController
{
    public function __construct(private EmployeeService $employeeService) {}

    #[Route('/api/employee', name: 'create_employee', methods: ['POST'])]
    public function createEmployee(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data)
                throw new BadRequestHttpException('Nieprawidłowe dane!');

            $employee = $this->employeeService->createEmployee($data);

            return $this->json([
                "response" => [
                    'message' => 'Pracownik został dodany!',
                    "id" => $employee->getId()
                ]
            ], Response::HTTP_CREATED);
        } catch (BadRequestHttpException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
