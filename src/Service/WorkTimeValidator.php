<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Service\WorkTimeConfig;

class WorkTimeValidator
{
    public function __construct(private WorkTimeConfig $config) {}

    public function validate(?string $start, ?string $end)
    {
        if (!isset($start) || trim($start) == "" || !isset($end) || trim($end) == "")
            throw new BadRequestHttpException('Puste daty czasu pracy!');

        if ($start >= $end) {
            throw new BadRequestHttpException("Nieprawidłowe daty czasu pracy!");
        }

        $timeMinutes = round(abs(strtotime($start) - strtotime($end)) / 60);

        if ($timeMinutes > 60 * $this->config->getMaxHours())
            throw new BadRequestHttpException('Zbyt duży czas pracy!');

        return true;
    }
}
