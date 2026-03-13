<!-- Registration Procedure Modal -->
<div class="modal fade" id="registrationProcedureModal" tabindex="-1" aria-labelledby="registrationProcedureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-primary text-white p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 p-2 rounded-3 me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    </div>
                    <h5 class="modal-title fw-bold" id="registrationProcedureModalLabel">{{ __('Registration Procedure') }}</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light" style="height: 75vh;">
                <iframe id="registrationProcedureFrame" src="{{ route('registration-procedure.view') }}" frameborder="0" style="width: 100%; height: 100%;"></iframe>
            </div>
            <div class="modal-footer border-0 p-4 bg-white d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ __('Follow the steps in the document to complete your registration.') }}
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" onclick="printProcedure()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        {{ __('Print') }}
                    </button>

                    <a href="{{ route('payment-procedure.download') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        {{ __('Download') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printProcedure() {
        const frame = document.getElementById('registrationProcedureFrame');
        if (frame) {
            frame.contentWindow.focus();
            frame.contentWindow.print();
        }
    }
</script>
