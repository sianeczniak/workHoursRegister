<?php

namespace App\Service;

use App\Service\WorkTimeConfig;

class WorkTimeCalculator
{
    public function __construct(private WorkTimeConfig $config) {}

    public function calculateWorkTime(array $workTimes, string $type): array
    {
        $returnData = [];
        if ($type == 'day') {
            $returnData = [
                "standardHours" => $this->config->roundDailyWorkTimeToHours($workTimes['timeMinutes']),
                "standardRate" => $this->config->getRate(),
            ];

            $returnData['total'] = $returnData['standardHours'] * $returnData['standardRate'];
        } else if ($type == 'month') {

            $returnData = [
                "standardHours" => 0,
                "standardRate" => $this->config->getRate(),
                "overtimeHours" => 0,
                "overtimeRate" => $this->config->getOvertimeRate(),
            ];

            $monthlyStandardHours = $this->config->getMonthlyStandardHours();

            foreach ($workTimes as $key => $workTime) {
                $workTime = $workTime->toArray();
                if ($returnData['standardHours'] <= $monthlyStandardHours) {
                    $returnData['standardHours'] += $this->config->roundDailyWorkTimeToHours($workTime['timeMinutes']);

                    if ($returnData['standardHours'] > $monthlyStandardHours) {
                        $returnData['overtimeHours'] += $returnData['standardHours'] - $monthlyStandardHours;
                        $returnData['standardHours'] = $monthlyStandardHours;
                    }
                } else {
                    $returnData['overtimeHours'] += $this->config->roundDailyWorkTimeToHours($workTime['timeMinutes']);
                }
            }

            $returnData['total'] = $returnData['standardHours'] * $returnData['standardRate'] + $returnData['overtimeHours'] * $returnData['overtimeRate'];
        }

        return $returnData;
    }
}
