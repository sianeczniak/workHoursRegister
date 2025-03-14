<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Employee;
use App\Entity\WorkTime;

class WorkTimeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Tworzy czas pracy dla pracownika
     * @param array $data Dane dotyczące czasu pracy
     * @throws BadRequestHttpException gdy dane są nieprawidłowe
     */
    public function addWorkTime(array $data)
    {
        if (!isset($data['id'])) // employee id
            throw new BadRequestHttpException('Nie można utworzyć czasu pracy bez pracownika!');

        $employee = $this->entityManager->getRepository(Employee::class)->find($data['id']);
        if (!$employee) {
            throw new BadRequestHttpException("Nie znaleziono pracownika!");
        }

        if (!isset($data['start']) || trim($data['start']) == "" || !isset($data['end']) || trim($data['end']) == "")
            throw new BadRequestHttpException('Puste daty czasu pracy!');

        $timeStart = strtotime($data['start']);
        $timeEnd = strtotime($data['end']);

        if ($timeEnd <= $timeStart)
            throw new BadRequestHttpException('Nieprawidłowe daty czasu pracy!');

        $timeMinutes = round(abs($timeEnd - $timeStart) / 60);

        if ($timeMinutes > 720)
            throw new BadRequestHttpException('Zbyt duży czas pracy!');

        $timeStart = date("Y-m-d H:i", $timeStart);
        $timeStart = \DateTime::createFromFormat('Y-m-d H:i', $timeStart);
        $timeEnd = date("Y-m-d H:i", $timeEnd);
        $timeEnd = \DateTime::createFromFormat('Y-m-d H:i', $timeEnd);
        $dateStart = $timeStart->format("Y-m-d");
        $dateStart = \DateTime::createFromFormat('Y-m-d', $dateStart);

        $workTime = $this->entityManager->getRepository(WorkTime::class)->findBy([
            'employee' => $employee,
            'dateStart' => $dateStart,
        ]);

        if (count($workTime))
            throw new BadRequestHttpException('Czas pracy na ten dzień dla tego pracownika już istnieje!');

        $workTime = new WorkTime($timeStart, $timeEnd, $dateStart, $timeMinutes, $employee);

        $this->entityManager->persist($workTime);
        $this->entityManager->flush();

        return $workTime;
    }
}
