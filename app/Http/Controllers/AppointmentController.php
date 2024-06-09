<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Enums\Status;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermission('appointment.view')) {
            abort(403);
        }

        $appointments = Appointment::with('client', 'staff', 'service')->get();
        return Inertia::render('Backend/Appointments/Index', ['appointments' => $appointments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->hasPermission('appointment.create')) {
            abort(403);
        }

        $appointments = Appointment::all();
        $users = User::select('id', 'name as label')->get();
        $appointmentsTimeSlot = $this->appointmentTimeSlot();

        return Inertia::render('Backend/Appointments/Edit', compact('appointments', 'users', 'appointmentsTimeSlot'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission('appointment.create')) {
            abort(403);
        }
        
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'staff_id' => 'required|integer|exists:users,id',
            'service_id' => 'required|integer|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
        ]);      

        $validated['status'] = Status::PENDING;
        Appointment::create($validated);        

        return redirect()->back()->with('success', 'Appointment created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!Auth::user()->hasPermission('appointment.create')) {
            abort(403);
        }

        $appointment = Appointment::findOrFail($id);
        $staff_id = $appointment->staff_id;
        $service = Service::whereHas('provider', function ($query) use ($staff_id) {
            $query->where('user_id',  $staff_id);
        })->select('id', 'service_name as label')->get();

        $users = User::select('id', 'name as label')->get();
        $appointmentsTimeSlot = $this->appointmentTimeSlot();
        $status = [
            ['id' => 'pending', 'label' => Status::PENDING],
            ['id' => 'confirmed', 'label' => Status::CONFIRMED],
            ['id' => 'completed', 'label' => Status::COMPLETED],
            ['id' => 'canceled', 'label' => Status::CANCELED],
        ];
        return Inertia::render('Backend/Appointments/Edit', compact('appointment', 'service', 'users', 'status', 'appointmentsTimeSlot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!Auth::user()->hasPermission('appointment.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'client_id' => 'nullable|integer',
            'staff_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable|string',
            'status' => [Rule::enum(Status::class)],
        ]);

       

        $appointment->update($validated);
        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!Auth::user()->hasPermission('appointment.delete')) {
            abort(403);
        }
        $appoinment = Appointment::findOrFail($id);
        $appoinment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }

    public function getServicesByUser($userId)
    {
        $services = Service::whereHas('provider', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->select('id', 'service_name as label')->get();
    
        return response()->json($services);
    }   


    function appointmentTimeSlot($removed_times = [], $interval = 100) {
        // Initialize an empty array
        $time_array = array();
    
        // Loop from 900 to 2400 in increments of the specified interval
        for ($i = 900; $i <= 2400; $i += $interval) {
            // Format the time for display
            $formatted_time = sprintf('%02d.%02d', floor($i / 100), $i % 100);
            
            // Add AM/PM designation
            if ($i < 1200) {
                $formatted_time .= " AM";
            } else {
                $formatted_time .= " PM";
            }
    
            // Store the time in the array with the 24-hour format as the key
            $id = sprintf('%04d', $i);
            if (!in_array($id, $removed_times)) {
                $time_array[$id] = array('id' => $id, 'label' => $formatted_time);
            }
        }
    
        // Return the generated array
        return $time_array;
    }
    
   
    



}
