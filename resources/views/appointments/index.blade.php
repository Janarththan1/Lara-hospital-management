@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Appointments</h1>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Schedule New Appointment
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            @if(auth()->user()->isAdmin())
                            <th>Doctor</th>
                            @endif
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->name }}</td>
                            @if(auth()->user()->isAdmin())
                            <td>{{ $appointment->doctor->name }}</td>
                            @endif
                            <td>{{ $appointment->appointment_date->format('M d, Y - H:i A') }}</td>
                            <td>
                                @if($appointment->status === 'scheduled')
                                    <span class="badge bg-warning">Scheduled</span>
                                @elseif($appointment->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $appointments->links() }}
            </div>
        @else
            <p>No appointments found.</p>
        @endif
    </div>
</div>
@endsection