<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $timeFrame = $request->input('timeFrame', 'today');
        $status = $request->input('status', 'all');


         $query = Appointment::query();
         
         if ($status !== 'all') {
            $query->where('status', $status);
         }
 
         if ($timeFrame == 'today') {
            $query->whereDate('appointment_date', now()->toDateString());
        } elseif ($timeFrame == 'week') {
            $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($timeFrame == 'month') {
            $query->whereMonth('appointment_date', now()->month);
        } elseif ($timeFrame == 'year') {
            $query->whereYear('appointment_date', now()->year);
        }
 
         $appointments = $query->with(['service', 'client', 'staff'])->get();
 
         return Inertia::render('Backend/Reports/Index', [
             'appointments' => $appointments,
             'filters' => [
                 'timeFrame' => $timeFrame,
                 'status' => $status,
             ]
         ]);
     }

}
