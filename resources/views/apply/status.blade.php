@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="text-center mb-5">
                <h1 class="display-5 mb-2">{{ __('Application Tracking') }}</h1>
                <p class="text-muted">{{ __('Enter your details below to track your application progress.') }}</p>
            </div>

            <div class="card glass-card">
                <div class="card-header">{{ __('Check Status') }}</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger px-4 py-3 border-0 rounded-4" role="alert">
                            <div class="d-flex align-items-center">
                                <span class="me-2">⚠️</span>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('status.check') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ __('Application Reference') }}</label>
                            <input type="text" name="application_ref" class="form-control" required placeholder="ACETEL-SC-202X-XXXXXX">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('Check Status') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
