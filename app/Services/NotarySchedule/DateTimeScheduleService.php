<?php

namespace App\Services\NotarySchedule;

use App\Models\NotarySchedule;
use App\Models\User;
use Carbon\Carbon;

class DateTimeScheduleService
{
    public function checkUserDate(User $user, string $date)
    {
        return NotarySchedule::where('notary_id', $user->id)->where('date', $date)->whereNull('time')->first();
    }

    public function checkUserDateTime(User $user, object $date, string $time)
    {
        return NotarySchedule::where('notary_id', $user->id)->where('date', $date)->where('time', $time)->first();
    }

    public function checkUserDayAndTime(User $user, $day, string $time)
    {
        return NotarySchedule::where('notary_id', $user->id)->where('day', $day)->where('time', $time)->first();
    }

    public function checkUserCurrentDayTime($data)
    {
        return NotarySchedule::where('notary_id', $data['notary_id'])
            ->where('date', $data['notary_id'])
            ->where('start_time', $data['notary_id'])
            ->first();
    }

    public function checkUserDayTimeIfExist($data)
    {
        $checkUserDateTime = $this->checkUserCurrentDayTime($data);

        $checkUserDateTime ? $checkUserDateTime->update($data) : NotarySchedule::create($data);
    }

    public function checkUserDateIfExist(User $user, $calendar)
    {
        $date = Carbon::parse($calendar['date'])->format('Y-m-d');

        $checkUserDate = $this->checkUserDate($user, $date);

        $checkUserDate ? $checkUserDate->update([
            'date' => $date,
            'notary_id' => $user->id,
            'is_populated' => $calendar['is_populated'],
        ]) : NotarySchedule::create([
            'date' => $date,
            'notary_id' => $user->id,
            'is_populated' => $calendar['is_populated'],
        ]);

        return $this->checkUserDate($user, $date);
    }

    public function checkUserDateTimeIfExist(User $user, object $date, string $time)
    {
        $checkUserDateTime = $this->checkUserDateTime($user, $date, $time);

        $checkUserDateTime ? $checkUserDateTime->update([
            'date' => $date->date,
            'start_time' => $time,
            'end_time' => $time,
            'notary_id' => $user->id,
            'parent_id' => $date->id,
            'is_populated' => $date->is_populated,
        ]) : NotarySchedule::create([
            'date' => $date->date,
            'start_time' => $time,
            'end_time' => $time,
            'notary_id' => $user->id,
            'parent_id' => $date->id,
            'is_populated' => $date->is_populated,
        ]);
    }
}
