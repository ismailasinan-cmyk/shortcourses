@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-5">
                <h1 class="h3 mb-2 fw-bold text-primary">{{ __('Admin Reports') }}</h1>
                <p class="text-muted">{{ __('Export system data for analysis.') }}</p>
            </div>

            <div class="row g-4">
                <!-- Application Report Card -->
                <div class="col-md-6">
                    <div class="card glass-card h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">{{ __('Applications Report') }}</h4>
                            <p class="text-muted mb-4">{{ __('Export a detailed list of all applications including status, course, and applicant details.') }}</p>
                            
                            <form action="{{ route('admin.reports.export') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="applications">
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">{{ __('From Date') }}</label>
                                        <input type="date" name="start_date" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">{{ __('To Date') }}</label>
                                        <input type="date" name="end_date" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    {{ __('Export Applications CSV') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payments Report Card -->
                <div class="col-md-6">
                    <div class="card glass-card h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">{{ __('Payments Report') }}</h4>
                            <p class="text-muted mb-4">{{ __('Export a log of all attempted and successful payments.') }}</p>
                            
                            <form action="{{ route('admin.reports.export') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="payments">
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">{{ __('From Date') }}</label>
                                        <input type="date" name="start_date" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">{{ __('To Date') }}</label>
                                        <input type="date" name="end_date" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-teal w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    {{ __('Export Payments CSV') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
