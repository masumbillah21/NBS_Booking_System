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
        $services = Service::select('id', 'service_name as label')->get();
        $users = User::select('id', 'name as label')->get();
        $status = [
            ['id' => 'pending', 'label' => Status::PENDING],
            ['id' => 'confirmed', 'label' => Status::CONFIRMED],
            ['id' => 'confirmed', 'label' => Status::COMPLETED],
            ['id' => 'completed', 'label' => Status::COMPLETED],
        ];
        return Inertia::render('Backend/Appointments/Edit', compact('appointments', 'services', 'users', 'status'));
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
            'client_id' => 'required|integer',
            'staff_id' => 'required|integer',
            'service_id' => 'required|integer',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'status' => [Rule::enum(Status::class)],
        ]);

        $service = Appointment::create($validated);    

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
        $services = Service::select('id', 'service_name as label')->get();
        $users = User::select('id', 'name as label')->get();
        $status = [
            ['id' => 'pending', 'label' => Status::PENDING],
            ['id' => 'confirmed', 'label' => Status::CONFIRMED],
            ['id' => 'confirmed', 'label' => Status::COMPLETED],
            ['id' => 'completed', 'label' => Status::COMPLETED],
        ];
        return Inertia::render('Backend/Appointments/Edit', compact('appointment', 'services', 'users', 'status'));
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
            'appointment_time' => 'nullable|date_format:H:i',
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

}
