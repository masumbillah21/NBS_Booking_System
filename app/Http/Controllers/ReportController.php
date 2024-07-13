<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function appointments(Request $request)
    {
        $timeFrame = $request->input('timeFrame', 'today');
        $status = $request->input('status', 'all');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

         $query = Appointment::query();
         
         if ($status !== 'all') {
            $query->where('status', $status);
         }
 
        if ($timeFrame == 'today') {
            $query->whereDate('appointment_date', now()->toDateString());
        }
        elseif ($timeFrame == 'week') {
            $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($timeFrame == 'month') {
            $query->whereMonth('appointment_date', now()->month)->whereYear('appointment_date', now()->year);
        } elseif ($timeFrame == 'year') {
            $query->whereYear('appointment_date', now()->year);
        } elseif ($timeFrame == 'custom' && $startDate && $endDate) {
            $query->whereBetween('appointment_date', [$startDate, $endDate]);
        }
 
         $appointments = $query->with(['service', 'client', 'staff', 'service.provider'])->get();

         return Inertia::render('Backend/Reports/Appointments', [
             'appointments' => $appointments,
             'filterItems' => [
                 'timeFrame' => $timeFrame,
                 'status' => $status
             ]
         ]);
     }

     public function exportAppointments()
     {
        $appointments = Appointment::with(['service', 'client', 'staff', 'service.provider'])->get();
        return Inertia::render('Backend/Reports/Export',
            ['appointments' => $appointments]);
     }

}
