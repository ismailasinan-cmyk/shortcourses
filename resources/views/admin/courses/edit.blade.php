@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="text-center mb-5">
                <h1 class="h3 mb-2 fw-bold text-primary">{{ __('Edit Course') }}</h1>
                <p class="text-muted">{{ __('Update the details and visibility of this course.') }}</p>
            </div>

            <div class="card glass-card">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('admin.courses.update', $course->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" id="category_select" class="form-control" required>
                                <option value="CISCO" {{ $course->category == 'CISCO' ? 'selected' : '' }}>CISCO</option>
                                <option value="ACETEL" {{ $course->category == 'ACETEL' ? 'selected' : '' }}>ACETEL</option>
                                <option value="new_category" {{ !in_array($course->category, ['CISCO', 'ACETEL']) ? 'selected' : '' }}>Create New Category...</option>
                            </select>
                        </div>

                        <div class="mb-3 {{ !in_array($course->category, ['CISCO', 'ACETEL']) ? '' : 'd-none' }}" id="new_category_div">
                            <label class="form-label">New Category Name</label>
                            <input type="text" name="new_category" id="new_category_input" class="form-control" 
                                value="{{ !in_array($course->category, ['CISCO', 'ACETEL']) ? $course->category : '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" class="form-control" value="{{ $course->course_name }}" list="course_suggestions" required>
                            <datalist id="course_suggestions">
                                <option value="IT Essentials">
                                <option value="Introduction to IOT">
                                <option value="CCNA I">
                                <option value="CCNA II">
                                <option value="CCNA III">
                                <option value="Machine Learning">
                                <option value="Cybersecurity">
                                <option value="Digital Literacy">
                            </datalist>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 text-center">
                                <label class="form-label d-block">{{ __('Course State') }}</label>
                                <div class="form-check form-switch d-inline-block">
                                    <input type="checkbox" name="status" class="form-check-input ms-0" id="status" {{ $course->status ? 'checked' : '' }} role="switch">
                                    <label class="form-check-label ms-2" for="status">{{ __('Active') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Code') }}</label>
                                <input type="text" name="code" class="form-control" value="{{ $course->code }}" required placeholder="e.g. CSC101">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Fee (NGN)') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">₦</span>
                                    <input type="number" name="fee" class="form-control border-start-0 ps-0" step="0.01" value="{{ $course->fee }}" required placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Duration') }}</label>
                                <input type="text" name="duration" class="form-control" value="{{ $course->duration }}" placeholder="{{ __('e.g. 3 Months') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="{{ __('Provide a brief overview of the course content...') }}">{{ $course->description }}</textarea>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                {{ __('Update Course') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('category_select').addEventListener('change', function() {
        var newCategoryDiv = document.getElementById('new_category_div');
        var newCategoryInput = document.getElementById('new_category_input');
        var courseSuggestions = document.getElementById('course_suggestions');
        
        // Clear existing options
        courseSuggestions.innerHTML = '';
        
        var courses = {
            'CISCO': [
                'IT Essentials', 'Introduction to IOT', 'CCNA I', 'CCNA II', 'CCNA III'
            ],
            'ACETEL': [
                'Machine Learning', 'Cybersecurity', 'Digital Literacy'
            ]
        };

        if (this.value === 'new_category') {
            newCategoryDiv.classList.remove('d-none');
            newCategoryInput.setAttribute('required', 'required');
            newCategoryInput.focus();
        } else {
            newCategoryDiv.classList.add('d-none');
            newCategoryInput.removeAttribute('required');
            if (this.value !== 'new_category' && this.value !== "{{ $course->category }}") {
                 newCategoryInput.value = '';
            }
            
            // Populate suggestions if category is found
            if (courses[this.value]) {
                courses[this.value].forEach(function(course) {
                    var option = document.createElement('option');
                    option.value = course;
                    courseSuggestions.appendChild(option);
                });
            }
        }
    });

    // Trigger change event IF it's not a custom category (to populate datalist), 
    // or if it IS custom (to show the input), but we've handled the input logic in the HTML class logic already.
    // However, we need to populate data list for standard categories.
    var currentCat = "{{ $course->category }}";
    if (['CISCO', 'ACETEL'].includes(currentCat)) {
        document.getElementById('category_select').dispatchEvent(new Event('change'));
    }
</script>
@endpush
