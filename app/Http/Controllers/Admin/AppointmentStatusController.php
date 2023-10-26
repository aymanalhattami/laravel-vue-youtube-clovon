<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentStatusController extends Controller
{
    public function index()
    {
        $status = AppointmentStatus::cases();

        return collect($status)->map(function($status){
            return [
                'name' => $status->name,
                'value' => $status->value,
                'color' => $status->color(),
                'count' => Appointment::where('status', $status->value)->count(),
            ];
        });
    }
}
