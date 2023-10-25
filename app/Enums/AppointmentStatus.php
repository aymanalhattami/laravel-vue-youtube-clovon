<?php

namespace App\Enums;

enum AppointmentStatus: int
{
    case Scheduled = 1;
    case Confirmed = 2;
    case Cancelled = 3;

    public function color()
    {
        return match ($this) {
            AppointmentStatus::Scheduled => 'primary',
            AppointmentStatus::Confirmed => 'success',
            AppointmentStatus::Cancelled => 'danger',
        };
    }
}
