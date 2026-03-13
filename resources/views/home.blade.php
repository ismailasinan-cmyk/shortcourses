@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between mb-5">
                <div>
                    <h1 class="h2 mb-1 fw-bold text-primary">{{ __('My Dashboard') }}</h1>
                    <p class="text-muted">{{ __('Welcome back,') }} {{ auth()->user()->firstname }}! {{ __('Track your progress below.') }}</p>
                </div>
                <a href="{{ route('apply') }}" class="btn btn-primary btn-lg shadow-sm rounded-pill px-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    {{ __('New Application') }}
                </a>
            </div>

            @if (session('status'))
                <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card glass-card border-0 shadow-sm overflow-hidden mb-5">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-50 text-uppercase text-muted small fw-bold tracking-wider">
                                <tr>
                                    <th scope="col" class="ps-4 py-3">{{ __('Reference') }}</th>
                                    <th scope="col" class="py-3">{{ __('Course') }}</th>
                                    <th scope="col" class="py-3">{{ __('Fee') }}</th>
                                    <th scope="col" class="py-3">{{ __('Status') }}</th>
                                    <th scope="col" class="py-3">{{ __('Progress') }}</th>
                                    <th scope="col" class="pe-4 py-3 text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse($applications as $application)
                                    <tr>
                                        <td class="ps-4 py-4">
                                            <div class="fw-bold text-dark">{{ $application->application_ref }}</div>
                                            <div class="small text-muted">{{ $application->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="py-4">
                                            <div class="fw-bold text-primary">{{ $application->course->course_name }}</div>
                                        </td>
                                        <td class="py-4">
                                            <div class="fw-bold text-dark">₦{{ number_format($application->amount + $application->application_fee_amount, 2) }}</div>
                                            <div class="small text-muted">{{ __('Total Fee') }}</div>
                                        </td>
                                        <td class="py-4">
                                            <div class="d-flex flex-column gap-2 mb-0">
                                                <!-- App Fee -->
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="small text-muted me-2">{{ __('App Fee:') }}</span>
                                                    <span class="badge bg-{{ $application->application_fee_status === 'PAID' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $application->application_fee_status === 'PAID' ? 'success' : 'warning' }} rounded-pill px-2 py-1 small">
                                                        {{ $application->application_fee_status }}
                                                    </span>
                                                </div>
                                                <!-- Course Fee -->
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="small text-muted me-2">{{ __('Course:') }}</span>
                                                    <span class="badge bg-{{ $application->course_fee_status === 'PAID' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $application->course_fee_status === 'PAID' ? 'success' : 'secondary' }} rounded-pill px-2 py-1 small">
                                                        {{ $application->course_fee_status }}
                                                    </span>
                                                </div>
                                                <!-- Admission Status -->
                                                <div class="d-flex align-items-center justify-content-between border-top pt-1 mt-1">
                                                    <span class="small text-muted me-2">{{ __('Admission:') }}</span>
                                                    <span class="badge bg-{{ $application->admission_status === 'ADMITTED' ? 'success' : ($application->admission_status === 'PENDING' ? 'secondary' : 'danger') }} bg-opacity-10 text-{{ $application->admission_status === 'ADMITTED' ? 'success' : ($application->admission_status === 'PENDING' ? 'secondary' : 'danger') }} rounded-pill px-2 py-1 small">
                                                        {{ $application->admission_status }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4" style="min-width: 150px;">
                                            @php
                                                $progress = 10;
                                                if ($application->application_fee_status === 'PAID') $progress = 40;
                                                if ($application->course_fee_status === 'PAID' && $application->application_fee_status === 'PAID') $progress = 80;
                                                if ($application->admission_status === 'ADMITTED') $progress = 100;
                                            @endphp
                                            <div class="progress rounded-pill bg-light" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $progress == 100 ? 'success' : ($progress >= 66 ? 'teal' : 'primary') }}" 
                                                     role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                 {{ $progress }}% {{ __('Complete') }}
                                            </div>
                                        </td>
                                        <td class="pe-4 py-4 text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical text-muted"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('applications.review', $application->application_ref) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                            {{ __('View Details') }}
                                                        </a>
                                                    </li>
                                                    
                                                    @if($application->payment_status === 'PAID')
                                                        <li>
                                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2 py-2" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $application->id }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                                                {{ __('View Receipt') }}
                                                            </button>
                                                        </li>
                                                    @endif

                                                    @if($application->payment_status === 'PENDING')
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-warning fw-bold" href="{{ route('applications.review', $application->application_ref) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                                                {{ __('Continue to Payment') }}
                                                            </a>
                                                        </li>
                                                    @endif

                                                    @if($application->admission_status === 'ADMITTED')
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2 py-2 text-success fw-bold" data-bs-toggle="modal" data-bs-target="#admissionModal{{ $application->id }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                                {{ __('Admission Letter') }}
                                                            </button>
                                                        </li>
                                                    @endif

                                                    @if($application->certificate_path)
                                                        <li>
                                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2 py-2 text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#certificateModal{{ $application->id }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                                                {{ __('Download Certificate') }}
                                                            </button>
                                                        </li>
                                                    @endif

                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item d-flex align-items-center gap-2 py-2" data-bs-toggle="modal" data-bs-target="#registrationProcedureModal">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                                            {{ __('Registration Procedure') }}
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="py-4">
                                                <div class="bg-primary bg-opacity-10 d-inline-flex p-4 rounded-circle mb-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#1E3A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                                </div>
                                                <h4 class="fw-bold text-dark">{{ __('No active applications') }}</h4>
                                                <p class="text-muted mx-auto mb-4" style="max-width: 400px;">{{ __('Start your learning journey today by applying to one of our courses.') }}</p>
                                                <a href="{{ route('apply') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">{{ __('Browse Courses') }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h4 class="fw-bold mb-4">{{ __('Recommended Courses') }}</h4>
                <div class="card glass-card border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary">{{ __('Explore More Learning Opportunities') }}</h5>
                            <p class="text-muted mb-0 small">{{ __('Our catalog is constantly updated with new industry-relevant courses.') }}</p>
                        </div>
                        <a href="{{ url('/') }}" class="btn btn-outline-primary rounded-pill">{{ __('View Catalog') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .tracking-wider {
        letter-spacing: 0.05em;
    }
    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@foreach($applications as $application)



    @if($application->admission_status === 'ADMITTED')
        <!-- Admission Modal for {{ $application->application_ref }} -->
        <div class="modal fade" id="admissionModal{{ $application->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-success">{{ __('Admission Letter') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0" style="height: 500px;">
                        <iframe src="{{ route('applications.view-admission', $application->application_ref) }}" frameborder="0" style="width: 100%; height: 100%;"></iframe>
                    </div>
                    <div class="modal-footer border-0">
                         <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <a href="{{ route('applications.download-admission', $application->application_ref) }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            {{ __('Download PDF') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($application->payment_status === 'PAID')
        <!-- Receipt Modal for {{ $application->application_ref }} -->
        <div class="modal fade" id="receiptModal{{ $application->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-teal">{{ __('Payment Receipt') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0" style="height: 500px;">
                        <iframe src="{{ route('receipt.view', $application->application_ref) }}" frameborder="0" style="width: 100%; height: 100%;"></iframe>
                    </div>
                    <div class="modal-footer border-0">
                         <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <a href="{{ route('receipt.download', $application->application_ref) }}" class="btn btn-teal rounded-pill px-4 fw-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            {{ __('Download PDF') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($application->certificate_path)
        <!-- Certificate Modal for {{ $application->application_ref }} -->
        <div class="modal fade" id="certificateModal{{ $application->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-primary">{{ __('Course Certificate') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary opacity-25 mb-3"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                            <h4 class="fw-bold">{{ __('Congratulations!') }}</h4>
                            <p class="text-muted">{{ __('Your certificate for') }} <strong>{{ $application->course->course_name }}</strong> {{ __('is ready.') }}</p>
                        </div>
                        <div class="p-4 bg-light rounded-4 mb-4">
                            <div class="text-muted small mb-2">{{ __('Issued on') }}</div>
                            <div class="fw-bold">{{ $application->certificate_issued_at ? \Carbon\Carbon::parse($application->certificate_issued_at)->format('M d, Y') : __('N/A') }}</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <a href="{{ route('applications.download-certificate', $application->application_ref) }}" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            {{ __('Download Certificate') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@include('components.registration-procedure-modal')
@endsection
