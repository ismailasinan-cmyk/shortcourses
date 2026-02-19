@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h3 mb-0 fw-bold text-primary">{{ __('Applications Management') }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success px-4 py-3 border-0 rounded-4 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <span class="me-2">✅</span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card glass-card mb-5">
        <div class="card-body p-4">
            <form action="{{ route('admin.applications.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">{{ __('Search') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, Ref, Email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">{{ __('Payment Status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">{{ __('Course Filter') }}</label>
                    <select name="course_id" class="form-select">
                        <option value="">{{ __('All Courses') }}</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">{{ __('From Date') }}</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">{{ __('To Date') }}</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            {{ __('Filter') }}
                        </button>
                        <button type="submit" name="export" value="true" class="btn btn-teal px-3">
                            <span class="small fw-bold">{{ __('Export') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light bg-opacity-50 text-muted small">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase fw-bold">{{ __('Reference') }}</th>
                            <th class="py-3 text-uppercase fw-bold">{{ __('Applicant') }}</th>
                            <th class="py-3 text-uppercase fw-bold">{{ __('Course') }}</th>
                            <th class="py-3 text-uppercase fw-bold">{{ __('Fee (₦)') }}</th>
                            <th class="py-3 text-uppercase fw-bold text-center">{{ __('Payment Status') }}</th>
                            <th class="py-3 text-uppercase fw-bold text-center">{{ __('Admission Status') }}</th>
                            <th class="py-3 text-uppercase fw-bold">{{ __('Date') }}</th>
                            <th class="pe-4 py-3 text-uppercase fw-bold text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="font-monospace small text-primary fw-bold">{{ $app->application_ref }}</span>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold">{{ $app->surname }} {{ $app->first_name }}</div>
                                <small class="text-muted">{{ $app->email }}</small>
                            </td>
                            <td class="py-3">
                                <div class="small fw-medium">{{ Str::limit($app->course?->course_name ?? 'N/A', 35) }}</div>
                                <small class="text-muted">{{ $app->course?->category ?? 'N/A' }}</small>
                            </td>
                            <td class="py-3 fw-bold text-teal">₦{{ number_format($app->amount, 2) }}</td>
                            <td class="py-3 text-center">
                                <span class="status-badge" style="background: {{ $app->payment_status == 'PAID' ? 'rgba(16, 185, 129, 0.1)' : ($app->payment_status == 'PENDING' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(239, 68, 68, 0.1)') }}; color: {{ $app->payment_status == 'PAID' ? '#059669' : ($app->payment_status == 'PENDING' ? '#D97706' : '#DC2626') }};">
                                    {{ $app->payment_status }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <form action="{{ route('admin.applications.update-status', $app->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="admission_status" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 py-1" onchange="this.form.submit()" style="font-size: 0.75rem; width: auto; margin: 0 auto; min-width: 100px; color: {{ $app->admission_status == 'ADMITTED' ? '#059669' : ($app->admission_status == 'PENDING' ? '#1E3A8A' : '#DC2626') }}; fw-bold">
                                        <option value="PENDING" {{ $app->admission_status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                        <option value="ADMITTED" {{ $app->admission_status == 'ADMITTED' ? 'selected' : '' }}>ADMITTED</option>
                                        <option value="REJECTED" {{ $app->admission_status == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3 text-muted small">{{ $app->created_at->format('M d, Y') }}</td>
                            <td class="pe-4 py-3 text-end">
                                @php
                                    $receiptPayment = $app->payments->whereNotNull('receipt_path')->sortByDesc('created_at')->first();
                                    $pendingPayment = $app->payments->where('status', 'PENDING')->sortByDesc('created_at')->first();
                                @endphp

                                @if($receiptPayment)
                                    <div class="d-flex gap-1 justify-content-end mb-2">
                                        <button type="button" class="btn btn-sm btn-info text-white shadow-sm rounded-pill px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#receiptPreviewModal" 
                                            data-app-ref="{{ $app->application_ref }}"
                                            data-rrr="{{ $receiptPayment->remita_rrr }}"
                                            data-view-url="{{ route('admin.applications.view-receipt', $app->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            {{ __('Receipt') }}
                                        </button>
                                        @if($pendingPayment && $app->payment_status !== 'PAID')
                                            <form action="{{ route('admin.applications.approve-payment', $app->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this payment? Ref: {{ $app->application_ref }} RRR: {{ $pendingPayment->remita_rrr }}');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success shadow-sm rounded-pill px-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif


                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($applications->hasPages())
                <div class="px-4 py-3 border-top bg-light bg-opacity-25">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>



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

<!-- Receipt Preview Modal -->
<div class="modal fade" id="receiptPreviewModal" tabindex="-1" aria-labelledby="receiptPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="receiptPreviewModalLabel">{{ __('Payment Receipt Preview') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light d-flex align-items-center justify-content-center" style="min-height: 400px;">
                <div id="loader" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <iframe id="receiptFrame" src="" frameborder="0" style="width: 100%; height: 600px; display: none;"></iframe>
                <img id="receiptImage" src="" class="img-fluid" style="display: none; max-height: 600px;">
                <div id="errorMsg" class="text-danger p-4 text-center" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <h5>{{ __('Unable to load receipt') }}</h5>
                </div>
            </div>
            <div class="modal-footer border-0 bg-white py-3">
                <span class="me-auto text-muted small px-3">
                    Ref: <span id="modalAppRef" class="fw-bold"></span>
                </span>
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('receiptPreviewModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const ref = button.getAttribute('data-app-ref');
            const viewUrl = button.getAttribute('data-view-url');
            
            const frame = document.getElementById('receiptFrame');
            const img = document.getElementById('receiptImage');
            const loader = document.getElementById('loader');
            const error = document.getElementById('errorMsg');
            const refSpan = document.getElementById('modalAppRef');
            
            // Reset state
            frame.style.display = 'none';
            img.style.display = 'none';
            error.style.display = 'none';
            loader.style.display = 'block';
            frame.src = '';
            img.src = '';
            
            refSpan.textContent = ref + (button.getAttribute('data-rrr') ? ' | RRR: ' + button.getAttribute('data-rrr') : '');
            
            // Try to detect file type from URL or just try to load
            fetch(viewUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Not found');
                    const contentType = response.headers.get('content-type');
                    return response.blob().then(blob => ({ blob, contentType }));
                })
                .then(({ blob, contentType }) => {
                    const objectUrl = URL.createObjectURL(blob);
                    loader.style.display = 'none';
                    
                    if (contentType.includes('image')) {
                        img.src = objectUrl;
                        img.style.display = 'block';
                    } else {
                        frame.src = objectUrl;
                        frame.style.display = 'block';
                    }
                })
                .catch(err => {
                    loader.style.display = 'none';
                    error.style.display = 'block';
                    console.error('Modal Load Error:', err);
                });
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('receiptFrame').src = '';
            document.getElementById('receiptImage').src = '';
        });
    }
</script>
@endpush
