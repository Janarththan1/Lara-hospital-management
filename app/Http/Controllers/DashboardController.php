<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->doctorDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
        ];

        $recent_appointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_appointments'));
    }

    private function doctorDashboard()
    {
        $doctor = auth()->user();
        
        $stats = [
            'my_patients' => $doctor->patients()->count(),
            'my_appointments' => $doctor->appointments()->count(),
            'today_appointments' => $doctor->appointments()->whereDate('appointment_date', today())->count(),
            'pending_appointments' => $doctor->appointments()->where('status', 'scheduled')->count(),
        ];

        $upcoming_appointments = $doctor->appointments()
            ->with('patient')
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        return view('doctor.dashboard', compact('stats', 'upcoming_appointments'));
    }
}