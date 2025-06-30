@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Doctor Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">My Patients</h5>
                <h2>{{ $stats['my_patients'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Appointments</h5>
                <h2>{{ $stats['my_appointments'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Today's Appointments</h5>
                <h2>{{ $stats['today_appointments'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <h2>{{ $stats['pending_appointments'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Upcoming Appointments</h5>
            </div>
            <div class="card-body">
                @if($upcoming_appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcoming_appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ $appointment->appointment_date->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ ucfirst($appointment->status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No upcoming appointments.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('patients.create') }}" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-plus"></i> Add New Patient
                </a>
                <a href="{{ route('appointments.create') }}" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-calendar-plus"></i> Schedule Appointment
                </a>
                <a href="{{ route('patients.index') }}" class="btn btn-info btn-block">
                    <i class="fas fa-list"></i> View All Patients
                </a>
            </div>
        </div>
    </div>
</div>
@endsection