@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-5 gap-3">
                <h1 class="h2 mb-0">{{ __('Administrative Dashboard') }}</h1>
                <div class="text-muted small">{{ now()->format('l, jS F Y') }}</div>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-md-2">
                    <div class="card glass-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-2 bg-primary bg-opacity-10 rounded-3 me-3 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                </div>
                                <div class="text-muted fw-medium small">{{ __('Total Apps') }}</div>
                            </div>
                            <h3 class="mb-0">{{ number_format($stats['total_applications']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card glass-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-2 bg-success bg-opacity-10 rounded-3 me-3 text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                </div>
                                <div class="text-muted fw-medium small">{{ __('Fully Paid') }}</div>
                            </div>
                            <h3 class="mb-0">{{ number_format($stats['paid_applications']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card glass-card h-100 border-start border-4 border-success">
                        <div class="card-body">
                            <div class="text-muted fw-medium small mb-2">{{ __('App Fees Paid') }}</div>
                            <h3 class="mb-0 text-success">{{ number_format($stats['app_fees_paid']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card glass-card h-100 border-start border-4 border-teal">
                        <div class="card-body">
                            <div class="text-muted fw-medium small mb-2">{{ __('Course Fees Paid') }}</div>
                            <h3 class="mb-0 text-teal">{{ number_format($stats['course_fees_paid']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card glass-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-2 bg-info bg-opacity-10 rounded-3 me-3 text-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                </div>
                                <div class="text-muted fw-medium small">{{ __('Admitted') }}</div>
                            </div>
                            <h3 class="mb-0">{{ number_format($stats['admitted_applications']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card glass-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-2 bg-info bg-opacity-10 rounded-3 me-3 text-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                </div>
                                <div class="text-muted fw-medium small">{{ __('Courses') }}</div>
                            </div>
                            <h3 class="mb-0">{{ number_format($stats['total_courses']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card glass-card">
                <div class="card-header d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    {{ __('Recent Applications') }}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="ps-4 py-3">{{ __('Reference') }}</th>
                                    <th class="py-3">{{ __('Applicant') }}</th>
                                    <th class="py-3">{{ __('Course') }}</th>
                                    <th class="py-3">{{ __('Amount (₦)') }}</th>
                                    <th class="py-3">{{ __('Status') }}</th>
                                    <th class="pe-4 py-3">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_applications as $app)
                                <tr>
                                    <td class="ps-4 py-3 font-monospace small text-primary fw-bold">{{ $app->application_ref }}</td>
                                    <td class="py-3">
                                        <div class="fw-bold">{{ $app->surname }} {{ $app->first_name }}</div>
                                        <small class="text-muted">{{ $app->email }}</small>
                                    </td>
                                    <td class="py-3">{{ Str::limit($app->course?->course_name ?? 'N/A', 30) }}</td>
                                    <td class="py-3 fw-medium">₦{{ number_format($app->amount, 2) }}</td>
                                    <td class="py-3">
                                        <div class="d-flex flex-column gap-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="small text-muted" style="font-size: 0.65rem;">App:</span>
                                                <span class="badge bg-{{ $app->application_fee_status == 'PAID' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $app->application_fee_status == 'PAID' ? 'success' : 'warning' }} rounded-pill px-2 py-0" style="font-size: 0.65rem;">
                                                    {{ $app->application_fee_status }}
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="small text-muted" style="font-size: 0.65rem;">Course:</span>
                                                <span class="badge bg-{{ $app->course_fee_status == 'PAID' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $app->course_fee_status == 'PAID' ? 'success' : 'secondary' }} rounded-pill px-2 py-0" style="font-size: 0.65rem;">
                                                    {{ $app->course_fee_status }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pe-4 py-3 text-muted">{{ $app->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
