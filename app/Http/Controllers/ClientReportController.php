<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientReportController extends Controller
{
    public function appointments(Request $request)
    {
        // Retrieve filter parameters with default values
        $timeFrame = $request->input('timeFrame', 'today');
        $status = $request->input('status', 'all');

        // Base query for appointments, filtered by the authenticated client's ID
        $query = Appointment::where('client_id', Auth::id());

        // Apply status filter if not 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Apply time frame filters
        if ($timeFrame == 'today') {
            $query->whereDate('appointment_date', now()->toDateString());
        } elseif ($timeFrame == 'week') {
            $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($timeFrame == 'month') {
            $query->whereMonth('appointment_date', now()->month);
        } elseif ($timeFrame == 'year') {
            $query->whereYear('appointment_date', now()->year);
        }

        // Fetch the filtered appointments with related data
        $appointments = $query->with(['service', 'client', 'staff'])->get();

        // Return the data to the Inertia Vue component
        return Inertia::render('Backend/Reports/ClientReport', [
            'appointments' => $appointments,
            'filterItems' => [
                'timeFrame' => $timeFrame,
                'status' => $status
            ]
        ]);
    }
}
