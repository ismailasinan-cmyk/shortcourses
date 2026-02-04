@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4">
                    <h4 class="mb-0 fw-bold">{{ __('Payment Instructions') }}</h4>
                </div>
                <div class="card-body p-5">
                    <div class="alert alert-info border-0 rounded-4 d-flex align-items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-3 flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <div>
                            {{ __('Please review your application details below and proceed to make payment via Remita.') }}
                        </div>
                    </div>

                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <small class="text-uppercase text-muted fw-bold tracking-wider">{{ __('Application Reference') }}</small>
                                <div class="h5 mb-0 fw-bold text-dark mt-1">{{ $application->application_ref }}</div>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-uppercase text-muted fw-bold tracking-wider">{{ __('Course') }}</small>
                                <div class="h5 mb-0 fw-bold text-dark mt-1">{{ $application->course->course_name }}</div>
                            </div>
                            <div class="col-12">
                                <hr class="my-3 text-muted opacity-25">
                            </div>
                            <div class="col-sm-6">
                                <small class="text-uppercase text-muted fw-bold tracking-wider">{{ __('Amount Due') }}</small>
                                <div class="h4 mb-0 fw-bold text-primary mt-1">₦{{ number_format($application->amount, 2) }}</div>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <a href="{{ route('payment-procedure.download') }}" class="btn btn-outline-info rounded-pill btn-sm mt-1" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    {{ __('Download Procedure') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-secondary border-0 rounded-4 mb-4">
                        <div class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-3 flex-shrink-0 mt-1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            <div>
                                <h6 class="fw-bold mb-1">{{ __('Guidance') }}</h6>
                                <p class="mb-0 small text-muted">
                                    {{ __('Please download the payment procedure document above for detailed step-by-step instructions on how to complete your payment.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <a href="https://remita.net/" target="_blank" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold py-3 shadow-sm hover-scale transition-all">
                            {{ __('Go to Remita.net to Pay') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                        
                        <div class="text-center">
                            <span class="text-muted small">{{ __('Already paid?') }}</span>
                            <a href="{{ route('applications.confirm', $application->application_ref) }}" class="text-decoration-none fw-bold ms-1">
                                {{ __('Click here to confirm payment') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-wider { letter-spacing: 0.05em; }
    .hover-scale:hover { transform: scale(1.02); }
    .transition-all { transition: all 0.2s ease; }
</style>
@endsection
