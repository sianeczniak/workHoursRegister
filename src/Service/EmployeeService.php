<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Employee;

class EmployeeService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    /**
     * Tworzy nowego pracownika
     * @param array $data Dane pracownika
     * @return Employee Utworzony pracownik
     * @throws BadRequestHttpException gdy dane są nieprawidłowe
     */
    public function createEmployee(array $data): Employee
    {
        if (!isset($data['fullname']) || trim($data['fullname']) == "")
            throw new BadRequestHttpException('Nie można utworzyć pracownika bez podania imienia i nazwiska!');

        $employee = new Employee($data['fullname']);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $employee;
    }
}
