@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h4 class="fw-bold text-primary mb-0">{{ __('System Settings') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-5">
                        <h5 class="fw-bold mb-3">{{ __('Payment Procedure') }}</h5>
                        <p class="text-muted small mb-4">{{ __('Upload the document that explains the payment process to applicants. This will be available for download in the payment modal.') }}</p>

                        <div class="card bg-light border-0 rounded-4 p-4 mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1E3A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('Current Document') }}</h6>
                                    @if($setting && $setting->value)
                                        <a href="{{ Storage::url($setting->value) }}" target="_blank" class="text-decoration-none small fw-bold text-primary">
                                            {{ __('View Current File') }}
                                        </a>
                                    @else
                                        <span class="text-muted small">{{ __('No file uploaded yet.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">{{ __('Upload New Document') }}</label>
                                <input type="file" name="payment_procedure" class="form-control" required>
                                <div class="form-text">{{ __('Accepted formats: PDF, JPG, PNG. Max size: 5MB.') }}</div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                    {{ __('Upload & Save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
