@extends('layouts.app')

@section('content')
<div class="container-fluid text-center py-5 mb-5" style="background: radial-gradient(circle at top right, #1E3A8A, #1e1b4b); position: relative; overflow: hidden;">
    <div class="py-5 position-relative" style="z-index: 2;">
        <div class="mb-4">
            <img src="{{ asset('images/acetel-logo.jpeg') }}" alt="ACETEL Logo" width="120" height="120" class="rounded-circle shadow-lg bg-white p-2 border border-4 border-teal border-opacity-25 img-fluid" style="max-width: 100px; height: auto; @media(min-width: 768px) { max-width: 120px; }">
        </div>
        <h1 class="display-5 display-md-3 fw-bold mb-3 text-white">{{ config('app.name', 'ACETEL Short Courses') }}</h1>
        <p class="lead mb-5 text-white-50" style="max-width: 600px; margin: 0 auto;">{{ __('Advance your career with our specialized, industry-recognized short courses designed for the modern professional.') }}</p>
        
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            @auth
                <a href="{{ route('apply') }}" class="btn btn-teal btn-lg px-5 fw-bold shadow-lg">{{ __('Apply Now') }}</a>
                <a href="{{ Auth::user()->is_admin ? route('admin.dashboard') : route('home') }}" class="btn btn-outline-light btn-lg px-5 fw-bold">{{ __('My Dashboard') }}</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-teal btn-lg px-5 fw-bold shadow-lg">{{ __('Get Started') }}</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 fw-bold">{{ __('Sign In') }}</a>
            @endauth
        </div>
    </div>
    <!-- Subtle BG pattern -->
    <div style="position: absolute; top: -10%; right: -5%; width: 40%; height: 60%; background: radial-gradient(circle, rgba(20, 184, 166, 0.15) 0%, transparent 70%); border-radius: 50%;"></div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <h2 class="fw-bold mb-3">{{ __('Available Short Courses') }}</h2>
            <p class="text-muted">{{ __('Browse our selection of courses designed for professionals.') }}</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($courses as $course)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card h-100 border-0">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="mb-3">
                            <span class="badge bg-teal bg-opacity-10 text-teal px-3 py-2 rounded-pill font-monospace small">{{ $course->category }}</span>
                        </div>
                        <h4 class="card-title fw-bold mb-3 text-primary">{{ $course->course_name }}</h4>
                        <p class="card-text text-muted flex-grow-1 lh-base">{{ Str::limit($course->description, 120) }}</p>
                        
                        <div class="divider-subtle my-4"></div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">{{ __('Duration') }}</div>
                                <div class="fw-bold">{{ $course->duration }}</div>
                            </div>
                            <div class="text-end">
                                <div class="text-muted small mb-1">{{ __('Course Fee') }}</div>
                                <div class="h5 mb-0 fw-bold text-teal">₦{{ number_format($course->fee, 0) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <a href="{{ route('register') }}" class="btn btn-primary w-100 justify-content-center">{{ __('Enrol Now') }}</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-4 shadow-sm">
                    <p class="text-muted mb-0">{{ __('No short courses available at the moment.') }}</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endpush
