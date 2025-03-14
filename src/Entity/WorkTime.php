<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Employee;

#[ORM\Entity]
#[ORM\Table(name: 'worktimes')]
class WorkTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $timeStart;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $timeEnd;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateStart;

    #[ORM\Column(type: 'smallint')]
    private int $timeMinutes;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'workTimes')]
    #[ORM\JoinColumn(name: "employee_id", referencedColumnName: "uuid", onDelete: 'CASCADE', nullable: false)]
    private Employee $employee;

    public function __construct(\DateTimeInterface $timeStart, \DateTimeInterface $timeEnd, \DateTimeInterface $dateStart, int $timeMinutes, Employee $employee)
    {
        $this->timeStart = $timeStart;
        $this->timeEnd = $timeEnd;
        $this->dateStart = $dateStart;
        $this->timeMinutes = $timeMinutes;
        $this->employee = $employee;
    }
}
