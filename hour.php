<?php

use DateTime;
use DateInterval;

$allDaysTimes = [];
for($i = 0; $i <= 22; $i++){
    $allDaysTimes[] = getWorkTime();
}
echo json_encode($allDaysTimes);

$fp = fopen('file.csv', 'w');

foreach ($allDaysTimes as $daysFields) {
    fputcsv($fp, $daysFields);
}
fclose($fp);

function getWorkTime()
{
    try {
        $startTime = getStartTime();
        $lunchTime = getLunchTime($startTime);
        $returnFromLunch = getReturnFromLunch($lunchTime);
        $endTime = getEndTime($startTime);
        return [
            $startTime->format("H:i"),
            $lunchTime->format("H:i"),
            $returnFromLunch->format("H:i"),
            $endTime->format("H:i")
        ];

    } catch (\Exception $exception) {
        echo $exception;
    }
}

function getStartTime()
{
    $hour = rand(9, 10);
    $minute = rand(20, 40);
    $timeFormat = new DateTime();
    $timeFormat->setTime($hour, $minute);
    return $timeFormat;
}
function getLunchTime(DateTime $startTime)
{
    $lunchTime = clone($startTime);
    return $lunchTime->setTime(12, rand(20, 40));
}
function getReturnFromLunch(DateTime $lunchTime)
{
    $returnFromLunch = clone($lunchTime);
    $minimumMinute = (int) $returnFromLunch->format("i");
    $maximumMinute = $minimumMinute + 5;
    $randomMinute = rand($minimumMinute, $maximumMinute);
    return $returnFromLunch->setTime(13, $randomMinute);
}

function getEndTime(DateTime $startTime)
{
    $endTime = clone($startTime);
    $minimumMinute = (int) $endTime->format("i") + 3;
    $maximumMinute = $minimumMinute + 20;
    $randomMinute = rand($minimumMinute, $maximumMinute);
    $endHour = (int)$endTime->format("H") + 9;
    return $endTime->setTime($endHour, $randomMinute);
}