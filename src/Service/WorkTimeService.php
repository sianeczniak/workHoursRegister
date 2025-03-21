<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Employee;
use App\Entity\WorkTime;
use App\Service\WorkTimeValidator;
use App\Service\WorkTimeCalculator;

class WorkTimeService
{

    public function __construct(private EntityManagerInterface $entityManager, private WorkTimeValidator $validator, private WorkTimeCalculator $calculator) {}

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

        $data['start'] = isset($data['start']) ? $data['start'] : null;
        $data['end'] = isset($data['end']) ? $data['end'] : null;
        $this->validator->validate($data['start'], $data['end']);

        $timeStart = strtotime($data['start']);
        $timeEnd = strtotime($data['end']);
        $timeMinutes = round(abs($timeEnd - $timeStart) / 60);

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

    public function showWorkTime(array $data): array
    {
        if (!isset($data['id'])) // employee id
            throw new BadRequestHttpException('Nie znaleziono pracownika!');

        $employee = $this->entityManager->getRepository(Employee::class)->find($data['id']);
        if (!$employee) {
            throw new BadRequestHttpException("Nie znaleziono pracownika!");
        }

        if (!isset($data['date']) || trim($data['date']) == "")
            throw new BadRequestHttpException('Nie podano daty!');

        $detectDate = $this->detectDateFormat($data['date']);

        if ($detectDate['type'] == 'day') {
            $workTimes = $this->entityManager->getRepository(WorkTime::class)->findOneBy([
                'employee' => $employee,
                'dateStart' => $detectDate['date'],
            ]);

            return $this->calculator->calculateWorkTime($workTimes->toArray(), 'day');
        } else if ($detectDate['type'] == 'month') {

            $qb = $this->entityManager->createQueryBuilder();

            $qb->select('w')
                ->from(WorkTime::class, 'w')
                ->where('w.employee = :employee')
                ->andWhere('w.dateStart BETWEEN :startDate AND :endDate')
                ->setParameter('employee', $employee)
                ->setParameter('startDate', $detectDate['date']->format("Y-m-01"))
                ->setParameter('endDate', $detectDate['date']->format("Y-m-t"));

            $query = $qb->getQuery();
            $workTimes = $query->getResult();

            return $this->calculator->calculateWorkTime($workTimes, 'month');
        }

        return [];
    }

    private function detectDateFormat(string $date): array
    {
        $dateTmp = \DateTime::createFromFormat('Y-m-d', $date);
        if ($dateTmp && $dateTmp->format('Y-m-d') === $date)
            return ['date' => $dateTmp, 'type' => 'day'];

        $dateTmp = \DateTime::createFromFormat('d.m.Y', $date);
        if ($dateTmp && $dateTmp->format('d.m.Y') === $date)
            return ['date' => $dateTmp, 'type' => 'day'];

        $dateTmp = \DateTime::createFromFormat('Y-m', $date);
        if ($dateTmp && $dateTmp->format('Y-m') === $date)
            return ['date' => $dateTmp, 'type' => 'month'];

        $dateTmp = \DateTime::createFromFormat('m.Y', $date);
        if ($dateTmp && $dateTmp->format('m.Y') === $date)
            return ['date' => $dateTmp, 'type' => 'month'];
    }
}
