<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "employee")]

class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $uuid;

    #[ORM\Column(type: 'string', length: 100)]
    private string $fullname;

    #[ORM\OneToMany(targetEntity: WorkTime::class, mappedBy: 'employee')]
    private ?Collection $workTimes = null;

    public function __construct(string $fullname)
    {
        $this->fullname = $fullname;
        $this->workTimes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->uuid;
    }
}
