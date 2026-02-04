@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-success text-white p-4">
                    <h4 class="mb-0 fw-bold">{{ __('Confirm Payment') }}</h4>
                </div>
                <div class="card-body p-5">
                    @if(session('info'))
                        <div class="alert alert-info border-0 rounded-4 mb-4">
                            {{ session('info') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        {{ __('To verify your payment, please enter your Remita Retrieval Reference (RRR) and upload your payment receipt.') }}
                    </p>

                    <form action="{{ route('applications.confirm.process', $application->application_ref) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="rrr" class="form-label fw-bold text-muted small text-uppercase">{{ __('Remita Retrieval Reference (RRR)') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg rounded-pill bg-light border-0 px-4" id="rrr" name="rrr" placeholder="e.g. 123456789012" value="{{ old('rrr') }}" required maxlength="12">
                            @error('rrr')
                                <div class="text-danger small mt-1 ps-3">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="receipt" class="form-label fw-bold text-muted small text-uppercase">{{ __('Upload Receipt') }} <span class="text-danger">*</span></label>
                            <input type="file" class="form-control form-control-lg rounded-pill bg-light border-0 px-4" id="receipt" name="receipt" accept="image/*,.pdf" required>
                            <div class="form-text ms-3">{{ __('Supported formats: JPG, PNG, PDF. Max: 5MB') }}</div>
                            @error('receipt')
                                <div class="text-danger small mt-1 ps-3">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm hover-scale transition-all">
                                {{ __('Verify Payment') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('applications.review', $application->application_ref) }}" class="text-decoration-none text-muted small">
                                {{ __('Cancel and return to application') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.02); }
    .transition-all { transition: all 0.2s ease; }
</style>
@endsection
