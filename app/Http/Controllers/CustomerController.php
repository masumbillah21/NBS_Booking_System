<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Enums\Status;
use App\Models\Service;
use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (!Auth::user()->hasPermission('customer.view')) {
            abort(403);
        }
        
        $appointments = Appointment::where('client_id',$user->id)->with('client', 'staff', 'service')->get();
        return inertia()->render('Backend/Customer/Index',[ 'customerAppointments' => $appointments]);
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermission('customer.edit')) {
            abort(403);
        }

        $customerAppointment = Appointment::findOrFail($id);
        $services = Service::select('id', 'service_name as label')->get();
        $users = User::select('id', 'name as label')->get();
        $status = [
            ['id' => 'pending', 'label' => Status::PENDING],
            ['id' => 'confirmed', 'label' => Status::CONFIRMED],
            ['id' => 'confirmed', 'label' => Status::COMPLETED],
            ['id' => 'completed', 'label' => Status::COMPLETED],
        ];
        return inertia()->render('Backend/Customer/Edit', compact('customerAppointment', 'services', 'users', 'status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {

        if (!Auth::user()->hasPermission('customer.edit')) {
            abort(403);
        }
        // Validate the request data
        $validated = $request->validate([
            'client_id' => 'nullable|integer',
            'staff_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable|date_format:H:i',
        ]);

        // Check for conflicts
        $conflictingAppointment = Appointment::where('staff_id', $validated['staff_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->where('id', '!=', $appointment->id) // Exclude the current appointment from the check
            ->first();

        if ($conflictingAppointment) {
            return redirect()->back()->with('error', 'This staff member already has an appointment at the selected date and time.');
        }

        // Update the appointment
        $appointment->update($validated);
        return redirect()->route('customer.index')->with('success', 'Appointment updated successfully.');
    }


    public function cancel($id)
    {
        if (!Auth::user()->hasPermission('customer.cancel')) {
            abort(403);
        }

        $appointment = Appointment::find($id);

        $appointmentTime = Carbon::parse($appointment->appointment_time);

        // if ($appointmentTime->diffInHours(now()) < 1) {
        //     return redirect()->route('customer.index')->with('error', 'Cannot cancel an appointment less than 1 hour before its time.');
        // }

        if ($appointment->status !== 'completed') {
            $appointment->status = 'canceled';
            $appointment->save();

            return redirect()->route('customer.index')->with('success', 'Appointment cancelled successfully.');
        }

        return redirect()->route('customer.index')->with('success', 'Appointment cancelled successfully.');

        
    }
   // In your controller (e.g., CustomerController)
   public function reappointmentCreate($id)
   {

        if (!Auth::user()->hasPermission('customer.reappointment')) {
            abort(403);
        }
       // Get appointment details and related services, users, and statuses
       $appointment = Appointment::findOrFail($id);
       $services = Service::select('id', 'service_name as label')->get();
       $users = User::select('id', 'name as label')->get();
       $status = [
           ['id' => 'pending', 'label' => Status::PENDING],
           ['id' => 'confirmed', 'label' => Status::CONFIRMED],
           ['id' => 'completed', 'label' => Status::COMPLETED],
       ];
   
       // Pass data to the frontend
       return Inertia::render('Backend/Customer/Reappointment', compact('appointment', 'services', 'users', 'status'));
   }
   
   public function reappointment(Request $request)
   {

        if (!Auth::user()->hasPermission('customer.reappointment')) {
            abort(403);
        }
       // Find the original appointment by ID
       $appointment = Appointment::findOrFail($request->id);
   
       // Check if the status allows reappointment
       if ($appointment->status === 'canceled' || $appointment->status === 'completed') {
   
           // Validate the request data
           $validated = $request->validate([
               'client_id' => 'required|integer',
               'staff_id' => 'required|integer',
               'service_id' => 'required|integer',
               'appointment_date' => 'required|date',
               'appointment_time' => 'required|date_format:H:i',
               'status' => ['required', Rule::in(['pending'])], 
           ]);
   
           // Check if there's an existing appointment at the same date and time for the selected staff member
           $existingAppointment = Appointment::where('staff_id', $validated['staff_id'])
               ->where('appointment_date', $validated['appointment_date'])
               ->where('appointment_time', $validated['appointment_time'])
               ->first();
   
           if ($existingAppointment) {
               // If an appointment already exists at this time, return with an error
               return redirect()->back()->with('error', 'This staff member already has an appointment at the selected date and time.');
           }
   
           // Create a new appointment with the validated data
           $newAppointment = Appointment::create($validated);
   
           // Redirect to the customer index with a success message
           return redirect()->route('customer.index')->with('success', 'Appointment re-appointed successfully.');
       }
   

       return redirect()->route('customer.index')->with('error', 'Appointment cannot be re-appointed.');
   }


     /**
     * Show the form for creating a new resource.
     */
    public function createFeedback($id)
    {

        if (!Auth::user()->hasPermission('customer.feedback')) {
            abort(403);
        }

        $customerFeedback = Appointment::findOrFail($id);
        return inertia('Backend/Customer/FeedbackCreate', compact('customerFeedback'));
    }

    public function storeFeedback(Request $request)
    {
        if (!Auth::user()->hasPermission('customer.feedback')) {
            abort(403);
        }
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ]);
    
        // Check if feedback already exists for this appointment
        $existingFeedback = Feedback::where('appointment_id', $validated['appointment_id'])->first();
        if ($existingFeedback) {
            return redirect()->route('feedback.create', $validated['appointment_id'])
                ->withErrors(['appointment_id' => 'Feedback already exists for this appointment.']);
        }
    
        $appointment = Appointment::findOrFail($validated['appointment_id']);
    
        Feedback::create([
            'appointment_id' => $validated['appointment_id'],
            'client_id' => $appointment->client_id,
            'service_id' => $appointment->service_id,
            'rating' => $validated['rating'],
            'comments' => $validated['comments'],
        ]);
    
        return redirect()->route('customer.index')->with('success', 'Feedback successfully created.');
    }
    
    
}
