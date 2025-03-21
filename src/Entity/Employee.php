<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: "employee")]

class Employee
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $uuid;

    #[ORM\Column(type: 'string', length: 100)]
    private string $fullname;

    #[ORM\OneToMany(targetEntity: WorkTime::class, mappedBy: 'employee')]
    private ?Collection $workTimes = null;

    public function __construct(string $fullname)
    {
        $this->fullname = $fullname;
        $this->workTimes = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->uuid;
    }
}
