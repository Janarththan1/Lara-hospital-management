<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $patients = Patient::with('doctor')->paginate(10);
        } else {
            $patients = $user->patients()->paginate(10);
        }

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('patients.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'medical_history' => 'nullable|string',
            'doctor_id' => 'required|exists:users,id',
        ]);

        // If user is doctor, assign to themselves
        if (auth()->user()->isDoctor()) {
            $validated['doctor_id'] = auth()->id();
        }

        Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient created successfully.');
    }

    public function show(Patient $patient)
    {
        $this->authorizePatientAccess($patient);
        
        $appointments = $patient->appointments()
            ->with('doctor')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patients.show', compact('patient', 'appointments'));
    }

    public function edit(Patient $patient)
    {
        $this->authorizePatientAccess($patient);
        
        $doctors = User::where('role', 'doctor')->get();
        return view('patients.edit', compact('patient', 'doctors'));
    }

    public function update(Request $request, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'medical_history' => 'nullable|string',
            'doctor_id' => 'required|exists:users,id',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $this->authorizePatientAccess($patient);
        
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }

    private function authorizePatientAccess(Patient $patient)
    {
        if (auth()->user()->isDoctor() && $patient->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this patient.');
        }
    }
}