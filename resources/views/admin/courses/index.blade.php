@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">{{ __('Short Courses') }}</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-teal shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            {{ __('Add New Course') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success px-4 py-3 border-0 rounded-4 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <span class="me-2">✅</span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light bg-opacity-50 text-muted small">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase fw-bold">{{ __('Category') }}</th>
                            <th class="py-3 text-uppercase fw-bold">{{ __('Course Name') }}</th>
                            <th class="py-3 text-uppercase fw-bold text-center">{{ __('Code') }}</th>
                            <th class="py-3 text-uppercase fw-bold">{{ __('Fee (₦)') }}</th>
                            <th class="py-3 text-uppercase fw-bold">{{ __('Duration') }}</th>
                            <th class="py-3 text-uppercase fw-bold text-center">{{ __('Status') }}</th>
                            <th class="pe-4 py-3 text-uppercase fw-bold text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $course->category }}</span>
                            </td>
                            <td class="py-3 fw-bold">{{ $course->course_name }}</td>
                            <td class="py-3 text-center font-monospace small">{{ $course->code }}</td>
                            <td class="py-3 fw-medium text-teal">₦{{ number_format($course->fee, 2) }}</td>
                            <td class="py-3 text-muted">{{ $course->duration }}</td>
                            <td class="py-3 text-center">
                                <span class="status-badge" style="background: {{ $course->status ? 'rgba(16, 185, 129, 0.1)' : 'rgba(107, 114, 128, 0.1)' }}; color: {{ $course->status ? '#059669' : '#4B5563' }};">
                                    {{ $course->status ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-light border-end">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this course?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($courses->hasPages())
                <div class="px-4 py-3 border-top bg-light bg-opacity-25">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination .page-item:first-child,
    .pagination .page-item:last-child {
        display: none !important;
    }
    /* Remove arrows from selects */
    .form-select {
        background-image: none !important;
        padding-right: 0.75rem !important;
    }
</style>
@endpush
