<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::query()
            ->with('client:id,first_name,last_name')
            ->paginate()
            ->through(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'start_time' => $appointment->start_time->format('Y-m-d'),
                    'end_time' => $appointment->start_time->format('Y-m-d'),
                    'status' => [
                        'name' => $appointment->status->name,
                        'color' => $appointment->status->color()
                    ],
                    'client' => $appointment->client,
                ];
            });
    }
}
