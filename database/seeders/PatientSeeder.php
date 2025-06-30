<?php
namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run()
    {
        $doctor = User::where('role', 'doctor')->first();
        
        if ($doctor) {
            Patient::create([
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '555-0123',
                'date_of_birth' => '1990-05-15',
                'gender' => 'male',
                'address' => '123 Main St, City, State 12345',
                'medical_history' => 'No significant medical history.',
                'doctor_id' => $doctor->id,
            ]);

            Patient::create([
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '555-0124',
                'date_of_birth' => '1985-08-22',
                'gender' => 'female',
                'address' => '456 Oak Ave, City, State 12345',
                'medical_history' => 'Allergic to penicillin.',
                'doctor_id' => $doctor->id,
            ]);
        }
    }
}