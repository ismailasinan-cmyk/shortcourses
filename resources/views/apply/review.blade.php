@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Application Review') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center justify-content-between">
                            <div>
                                <strong class="d-block mb-1">{{ __('Success!') }}</strong>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill btn-sm px-3" data-bs-toggle="modal" data-bs-target="#registrationProcedureModal">
                                {{ __('View Registration Procedure') }}
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                            <strong class="d-block mb-1">{{ __('Error!') }}</strong>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        {{ __('Please review your application details. You cannot edit them after payment.') }}
                    </div>

                    <table class="table">
                        <tr>
                            <th>{{ __('Reference') }}</th>
                            <td><strong>{{ $application->application_ref }}</strong></td>
                        </tr>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <td>{{ $application->surname }} {{ $application->first_name }} {{ $application->other_name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Course') }}</th>
                            <td>{{ $application->course?->course_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Application Fee') }}</th>
                            <td>
                                <strong>₦{{ number_format($application->application_fee_amount, 2) }}</strong>
                                <span class="badge rounded-pill bg-{{ $application->application_fee_status == 'PAID' ? 'success' : 'warning' }} ms-2">
                                    {{ $application->application_fee_status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Course Fee') }}</th>
                            <td>
                                <strong>₦{{ number_format($application->amount, 2) }}</strong>
                                <span class="badge rounded-pill bg-{{ $application->course_fee_status == 'PAID' ? 'success' : 'secondary' }} ms-2">
                                    {{ $application->course_fee_status }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    <div class="row g-4 mb-4 mt-2">
                        <!-- Application Fee Card -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 {{ $application->application_fee_status === 'PAID' ? 'bg-success bg-opacity-10' : 'bg-warning bg-opacity-10' }}">
                                <div class="card-body p-4 text-center">
                                    <h6 class="text-uppercase fw-bold text-muted small mb-2">{{ __('Application Fee') }}</h6>
                                    <div class="h3 fw-bold mb-3 text-dark">₦{{ number_format($application->application_fee_amount, 2) }}</div>
                                    
                                    @if($application->application_fee_status !== 'PAID')
                                        <button type="button" class="btn btn-warning rounded-pill px-4 fw-bold w-100 shadow-sm" 
                                            onclick="openPaymentModal('{{ __('Application Fee') }}', '{{ number_format($application->application_fee_amount, 2) }}', 'APPLICATION_FEE')">
                                            {{ __('Pay Application Fee') }}
                                        </button>
                                    @else
                                        <div class="text-success fw-bold py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            {{ __('Payment Completed') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Course Fee Card -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 {{ $application->course_fee_status === 'PAID' ? 'bg-success bg-opacity-10' : 'bg-primary bg-opacity-10' }}">
                                <div class="card-body p-4 text-center">
                                    <h6 class="text-uppercase fw-bold text-muted small mb-2">{{ __('Course Fee') }}</h6>
                                    <div class="h3 fw-bold mb-3 text-dark">₦{{ number_format($application->amount, 2) }}</div>

                                    @if($application->course_fee_status !== 'PAID')
                                        <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold w-100 shadow-sm" 
                                            onclick="openPaymentModal('{{ __('Course Fee') }}', '{{ number_format($application->amount, 2) }}', 'COURSE_FEE')">
                                            {{ __('Pay Course Fee') }}
                                        </button>
                                    @else
                                        <div class="text-success fw-bold py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            {{ __('Payment Completed') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($application->payment_status === 'PAID')
                        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 bg-success bg-opacity-10 py-4 text-center">
                            <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-20 mb-3 text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            </div>
                            <h4 class="fw-bold text-success mb-2">{{ __('Payment Fully Verified') }}</h4>
                            <p class="text-muted mb-0">{{ __('Your application and course fees have been successfully paid and verified.') }}</p>
                        </div>
                    @endif

                    @if($application->application_fee_status !== 'PAID' && $application->course_fee_status !== 'PAID')
                        <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10 mb-4 border border-primary border-opacity-25">
                            <div class="card-body p-4 text-center">
                                <h5 class="fw-bold mb-2 text-primary">{{ __('Pay Both (Combined)') }}</h5>
                                <p class="text-muted small mb-3">{{ __('Pay both fees at once to get immediate admission processing.') }}</p>
                                <div class="h2 fw-bold mb-4 text-dark">₦{{ number_format($application->application_fee_amount + $application->amount, 2) }}</div>
                                <button type="button" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm border-0" 
                                    style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);"
                                    onclick="openPaymentModal('{{ __('Total Payment (Both Fees)') }}', '{{ number_format($application->application_fee_amount + $application->amount, 2) }}', 'BOTH')">
                                    {{ __('Pay Total ₦' . number_format($application->application_fee_amount + $application->amount, 0) . ' Now') }}
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($application->admission_status === 'ADMITTED' && $application->application_fee_status === 'PAID')
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('applications.download-admission', $application->application_ref) }}" class="btn btn-outline-success btn-lg rounded-pill fw-bold shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                {{ __('Download Admission Letter') }}
                            </a>
                        </div>
                    @elseif($application->admission_status === 'ADMITTED' && $application->application_fee_status !== 'PAID')
                        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-0">
                            <strong>{{ __('Admission Ready!') }}</strong> {{ __('Your admission is ready, but you must pay the application fee before you can download your admission letter.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('components.registration-procedure-modal')

<!-- Payment Gate Modal -->
<div class="modal fade" id="paymentGateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold text-primary">{{ __('Payment Gateway') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <div class="h6 text-muted mb-2 text-uppercase fw-bold tracking-wider small">{{ __('You are paying for') }}</div>
                    <div id="modalPaymentLabel" class="h4 fw-bold text-dark mb-1">...</div>
                    <div id="modalPaymentAmount" class="h2 fw-bold text-primary">₦0.00</div>
                </div>

                <div class="alert alert-warning border-0 rounded-4 small mb-4 text-start d-flex align-items-start shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 flex-shrink-0 mt-1 text-warning"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <div>
                        {{ __('IMPORTANT: Please read the payment procedure and copy your application reference') }} <strong>({{ $application->application_ref }})</strong> {{ __('before proceeding to Remita.') }}
                    </div>
                </div>

                <div class="d-grid gap-3">
                    <button type="button" class="btn btn-outline-primary rounded-pill py-2 fw-bold transition-all hvr-grow" data-bs-toggle="modal" data-bs-target="#registrationProcedureModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        {{ __('View Payment Procedure') }}
                    </button>
                    
                    <a href="https://remita.net/" target="_blank" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm border-0 hvr-grow" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                        {{ __('Proceed to Remita.net') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 pt-0 px-4">
                <a id="modalInstructionLink" href="#" class="text-muted small text-decoration-none fw-bold hover-primary">
                    {{ __('Having trouble? View instructions & confirm RRR') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-wider { letter-spacing: 0.1em; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .hvr-grow:hover { transform: scale(1.02); }
    .hover-primary:hover { color: #1e3a8a !important; }
</style>

<script>
    function openPaymentModal(label, amount, type) {
        try {
            document.getElementById('modalPaymentLabel').innerText = label;
            document.getElementById('modalPaymentAmount').innerText = '₦' + amount;
            
            // Update the detailed instruction link
            const baseUrl = "{{ route('applications.payment', $application->application_ref) }}";
            document.getElementById('modalInstructionLink').href = baseUrl + '?type=' + type;
            
            // Open the modal using Bootstrap global
            if (typeof bootstrap !== 'undefined') {
                var modalEl = document.getElementById('paymentGateModal');
                var myModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                myModal.show();
            } else {
                console.error('Bootstrap is not loaded');
                alert('An error occurred. Please refresh the page and try again.');
            }
        } catch (e) {
            console.error('Modal error:', e);
        }
    }
</script>
@endsection
