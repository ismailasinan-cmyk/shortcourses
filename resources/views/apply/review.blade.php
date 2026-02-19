@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Application Review') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="alert alert-info">
                        {{ __('Please review your application details. You cannot edit them after payment.') }}
                    </div>

                    <table class="table">
                        <tr>
                            <th>{{ __('Reference') }}</th>
                            <td><strong>{{ $application->application_ref }}</strong></td>
                        </tr>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <td>{{ $application->surname }} {{ $application->first_name }} {{ $application->other_name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Course') }}</th>
                            <td>{{ $application->course?->course_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Highest Qualification') }}</th>
                            <td>
                                @if($application->ssce_file_path)
                                    <a href="{{ asset('storage/' . $application->ssce_file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ __('View Document') }}</a>
                                @else
                                    <span class="text-muted">{{ __('Not Provided') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Relevant Qualifications') }}</th>
                            <td>
                                @if($application->relevant_qualifications_file_path)
                                    <a href="{{ asset('storage/' . $application->relevant_qualifications_file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ __('View Document') }}</a>
                                @else
                                    <span class="text-muted">{{ __('Not Provided') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Amount') }}</th>
                            <td><strong>₦{{ number_format($application->amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>
                                <span class="badge bg-{{ $application->payment_status == 'PAID' ? 'success' : 'warning' }}">
                                    {{ $application->payment_status }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    @if($application->payment_status == 'PENDING')
                        @php
                            $awaitingConfirmation = $application->payments->filter(function($p) {
                                return $p->status === 'PENDING' && !empty($p->receipt_path);
                            })->isNotEmpty();
                        @endphp

                        @if($awaitingConfirmation)
                             <div class="d-grid gap-2 mb-2">
                                <a href="{{ route('applications.payment.receipt.view', $application->application_ref) }}" target="_blank" class="btn btn-outline-info btn-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    {{ __('View Uploaded Receipt') }}
                                </a>
                            </div>
                            <div class="d-grid gap-2">
                                <form action="{{ route('applications.payment.receipt.delete', $application->application_ref) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this receipt? You will need to re-upload it.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-lg w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        {{ __('Delete & Re-upload') }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="d-grid gap-2">
                                <a href="{{ route('applications.payment', $application->application_ref) }}" class="btn btn-warning btn-lg fw-bold">
                                    {{ __('Pay Now') }}
                                </a>
                            </div>
                        @endif
                    @elseif($application->payment_status == 'PAID')
                        <div class="d-grid gap-2">
                            <a href="{{ route('receipt.download', $application->application_ref) }}" class="btn btn-primary btn-lg">{{ __('Download Receipt') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
