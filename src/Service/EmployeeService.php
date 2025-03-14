<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Employee;

class EmployeeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Tworzy nowego pracownika
     * @param array $data Dane pracownika
     * @return Employee Utworzony pracownik
     * @throws BadRequestHttpException gdy dane są nieprawidłowe
     */
    public function createEmployee(array $data): Employee
    {
        if (!isset($data['fullname']))
            throw new BadRequestHttpException('Cannot create employee without a fullname');

        $employee = new Employee($data['fullname']);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $employee;
    }
}
