<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::query()
            ->latest()
            ->with('client:id,first_name,last_name')
            ->when(request()->status, function($query){
                return $query->where('status', AppointmentStatus::from(request()->status));
            })
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

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'client_id' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);

        Appointment::create(array_merge($request->all(), ['status' => AppointmentStatus::Scheduled]));

        return response()->json(['message' => 'created successfully']);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'client_id' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);

        $appointment->update(array_merge($request->all(), ['status' => AppointmentStatus::Scheduled]));

        return response()->json(['message' => 'updated successfully']);
    }

    public function show(Appointment $appointment)
    {
        return $appointment;
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(['success' => 'deleted successfully']);
    }

    public function stats()
    {
        $appointmentCount = Appointment::query()
            ->when(\request('status') == 'scheduled', function($query){
                $query->where('status', AppointmentStatus::Scheduled);
            })
            ->when(\request('status') == 'confirmed', function($query){
                $query->where('status', AppointmentStatus::Confirmed);
            })
            ->when(\request('status') == 'cancelled', function($query){
                $query->where('status', AppointmentStatus::Cancelled);
            })
            ->count();

        return response()->json([
            'totalAppointmentsCount' => $appointmentCount
        ]);
    }
}
