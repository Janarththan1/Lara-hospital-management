<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $appointments = Appointment::with(['patient', 'doctor'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
        } else {
            $appointments = $user->appointments()
                ->with('patient')
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $patients = Patient::all();
            $doctors = User::where('role', 'doctor')->get();
        } else {
            $patients = $user->patients;
            $doctors = collect([$user]); // Only current doctor
        }

        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        // If user is doctor, assign to themselves
        if (auth()->user()->isDoctor()) {
            $validated['doctor_id'] = auth()->id();
        }

        // Check if patient belongs to doctor (for non-admin users)
        if (auth()->user()->isDoctor()) {
            $patient = Patient::find($validated['patient_id']);
            if ($patient->doctor_id !== auth()->id()) {
                return back()->with('error', 'You can only schedule appointments for your patients.');
            }
        }

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);
        
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);
        
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $patients = Patient::all();
            $doctors = User::where('role', 'doctor')->get();
        } else {
            $patients = $user->patients;
            $doctors = collect([$user]);
        }

        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);
        
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    private function authorizeAppointmentAccess(Appointment $appointment)
    {
        if (auth()->user()->isDoctor() && $appointment->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this appointment.');
        }
    }
}