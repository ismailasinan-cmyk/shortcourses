@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card glass-card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h4 class="fw-bold text-primary">{{ __('Change Password') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success border-0 rounded-4 shadow-sm" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger border-0 rounded-4 shadow-sm" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold text-muted">{{ __('Current Password') }}</label>
                            <input id="current_password" type="password" class="form-control rounded-pill @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="current-password">

                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-muted">{{ __('New Password') }}</label>
                            <input id="password" type="password" class="form-control rounded-pill @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirm" class="form-label fw-bold text-muted">{{ __('Confirm New Password') }}</label>
                            <input id="password_confirm" type="password" class="form-control rounded-pill" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm py-2">
                                {{ __('Update Password') }}
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-light rounded-pill fw-bold py-2">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
