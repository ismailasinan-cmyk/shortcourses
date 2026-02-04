@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-5">
                <h1 class="display-5 mb-2">{{ __('Short Course Application') }}</h1>
                <p class="text-muted">{{ __('Please provide accurate information to process your application.') }}</p>
            </div>

            <div class="card glass-card">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('apply.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Details -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-4 d-flex align-items-center">
                                <span class="badge bg-primary me-2">1</span>
                                {{ __('Personal Details') }}
                            </h5>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Surname') }} <span class="text-danger">*</span></label>
                                <input type="text" name="surname" class="form-control bg-light" required value="{{ old('surname', auth()->user()->surname) }}" readonly>
                                <small class="text-muted">{{ __('From your account profile') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control bg-light" required value="{{ old('first_name', auth()->user()->firstname) }}" readonly>
                                <small class="text-muted">{{ __('From your account profile') }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Other Name') }}</label>
                                <input type="text" name="other_name" class="form-control" value="{{ old('other_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control bg-light" required value="{{ old('email', auth()->user()->email) }}" readonly>
                                <small class="text-muted">{{ __('From your account profile') }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Gender') }} <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Date of Birth') }} <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control" required value="{{ old('date_of_birth') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Address') }} <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" required rows="2">{{ old('address') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <select id="country_select" class="form-control" style="width: auto; min-width: 150px;">
                                    <option value="Nigeria" selected>{{ __('Nigeria') }}</option>
                                    <option value="Others">{{ __('Others') }}</option>
                                </select>
                                <input type="text" id="country_input" class="form-control d-none" placeholder="{{ __('Enter Country Name') }}">
                            </div>
                            <input type="hidden" name="country" id="final_country" value="Nigeria">
                        </div>
                        
                        <!-- State/LGA - Nigeria (Dropdowns) -->
                        <div id="nigeria_address_container" class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('State') }} <span class="text-danger">*</span></label>
                                <select id="state_select" name="state_select" class="form-control">
                                    <option value="">{{ __('Select State') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('LGA') }} <span class="text-danger">*</span></label>
                                <select id="lga_select" name="lga_select" class="form-control">
                                    <option value="">{{ __('Select LGA') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- State/LGA - Others (Text Inputs) -->
                        <div id="other_address_container" class="row mb-4 d-none">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('State / Region / Province') }} <span class="text-danger">*</span></label>
                                <input type="text" id="state_input" name="state_input" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('City / LGA / Municipality') }} <span class="text-danger">*</span></label>
                                <input type="text" id="lga_input" name="lga_input" class="form-control">
                            </div>
                        </div>

                        <!-- Hidden fields to store final value submitted -->
                        <input type="hidden" name="state" id="final_state">
                        <input type="hidden" name="lga" id="final_lga">

                        <div class="divider-subtle"></div>

                        <!-- Qualification Details -->
                        <div class="mb-4 mt-5">
                            <h5 class="fw-bold mb-4 d-flex align-items-center">
                                <span class="badge bg-primary me-2">2</span>
                                {{ __('Qualification Details') }}
                            </h5>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">{{ __('Highest Qualification') }} <span class="text-muted">({{ __('Optional') }})</span></label>
                            <select id="highest_qualification" name="highest_qualification" class="form-control">
                                <option value="">{{ __('Select Qualification') }}</option>
                                <option value="SSCE" {{ old('highest_qualification') == 'SSCE' ? 'selected' : '' }}>SSCE (WAEC/NECO/NABTEB)</option>
                                <option value="Degree" {{ old('highest_qualification') == 'Degree' ? 'selected' : '' }}>Degree / HND</option>
                            </select>
                        </div>

                        <!-- SSCE Fields -->
                        <div id="ssce_container" class="d-none">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('SSCE Type') }} <span class="text-danger">*</span></label>
                                    <select name="ssce_type" class="form-control" id="ssce_type">
                                        <option value="">Select Type</option>
                                        <option value="WAEC" {{ old('ssce_type') == 'WAEC' ? 'selected' : '' }}>WAEC</option>
                                        <option value="NECO" {{ old('ssce_type') == 'NECO' ? 'selected' : '' }}>NECO</option>
                                        <option value="NABTEB" {{ old('ssce_type') == 'NABTEB' ? 'selected' : '' }}>NABTEB</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Exam Year') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="ssce_year" id="ssce_year" class="form-control" min="1990" max="{{ date('Y') }}" value="{{ old('ssce_year') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Exam Number') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="ssce_exam_number" id="ssce_exam_number" class="form-control" value="{{ old('ssce_exam_number') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Degree Fields -->
                        <div id="degree_container" class="d-none">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Degree Type') }} <span class="text-danger">*</span></label>
                                    <select name="degree_type" id="degree_type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="BSc" {{ old('degree_type') == 'BSc' ? 'selected' : '' }}>BSc</option>
                                        <option value="MSc" {{ old('degree_type') == 'MSc' ? 'selected' : '' }}>MSc</option>
                                        <option value="PhD" {{ old('degree_type') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        <option value="HND" {{ old('degree_type') == 'HND' ? 'selected' : '' }}>HND</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">{{ __('Institution Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="degree_institution" id="degree_institution" class="form-control" value="{{ old('degree_institution') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('Year') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="degree_year" id="degree_year" class="form-control" min="1980" max="{{ date('Y') }}" value="{{ old('degree_year') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('Class') }} <span class="text-danger">*</span></label>
                                    <select name="degree_class" id="degree_class" class="form-control">
                                        <option value="">Select Class</option>
                                        <option value="First Class" {{ old('degree_class') == 'First Class' ? 'selected' : '' }}>First Class</option>
                                        <option value="Second Class Upper" {{ old('degree_class') == 'Second Class Upper' ? 'selected' : '' }}>Second Class Upper</option>
                                        <option value="Second Class Lower" {{ old('degree_class') == 'Second Class Lower' ? 'selected' : '' }}>Second Class Lower</option>
                                        <option value="Third Class" {{ old('degree_class') == 'Third Class' ? 'selected' : '' }}>Third Class</option>
                                        <option value="Distinction" {{ old('degree_class') == 'Distinction' ? 'selected' : '' }}>Distinction</option>
                                        <option value="Upper Credit" {{ old('degree_class') == 'Upper Credit' ? 'selected' : '' }}>Upper Credit</option>
                                        <option value="Lower Credit" {{ old('degree_class') == 'Lower Credit' ? 'selected' : '' }}>Lower Credit</option>
                                        <option value="Pass" {{ old('degree_class') == 'Pass' ? 'selected' : '' }}>Pass</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="divider-subtle"></div>
                        
                        <!-- Additional Certifications -->
                        <div class="mb-4 mt-5">
                            <h5 class="fw-bold mb-3">{{ __('Additional Certifications') }} <span class="text-muted fs-6 fw-normal">({{ __('Optional, Max 5') }})</span></h5>
                            <div id="additional_certs_container">
                                <!-- Dynamic rows will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add_cert_btn">
                                <i class="bi bi-plus-circle me-1"></i> {{ __('Add Certification') }}
                            </button>
                        </div>

                        <div class="divider-subtle"></div>

                        <!-- Course Selection -->
                        <div class="mb-4 mt-5">
                            <h5 class="fw-bold mb-4 d-flex align-items-center">
                                <span class="badge bg-primary me-2">3</span>
                                {{ __('Course Selection') }}
                            </h5>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Select Category') }} <span class="text-danger">*</span></label>
                                <select id="category_select" name="category" class="form-control" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($courses->pluck('category')->unique() as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Select Course') }} <span class="text-danger">*</span></label>
                                <select id="course_select" name="short_course_id" class="form-control" required disabled>
                                    <option value="">{{ __('Select Course') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Course Details Display -->
                        <div id="course_details_container" class="mb-4 d-none">
                            <div class="card bg-primary bg-opacity-10 border-0 rounded-4">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="d-flex align-items-center">
                                                <div class="p-3 bg-white rounded-circle me-3 shadow-sm text-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                                </div>
                                                <div>
                                                    <div class="text-muted small fw-bold text-uppercase">{{ __('Course Fee') }}</div>
                                                    <div class="h4 mb-0 fw-bold text-primary" id="display_fee">₦0.00</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="p-3 bg-white rounded-circle me-3 shadow-sm text-teal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                </div>
                                                <div>
                                                    <div class="text-muted small fw-bold text-uppercase">{{ __('Duration') }}</div>
                                                    <div class="h4 mb-0 fw-bold text-teal" id="display_duration">---</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5 form-check bg-light p-4 rounded-4 border-0">
                            <input type="checkbox" name="declaration" class="form-check-input ms-0" id="declaration" required>
                            <label class="form-check-label ms-2" for="declaration">
                                {{ __('I hereby declare that the information provided in this application is correct and true to the best of my knowledge.') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">{{ __('Submit Application') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const courses = @json($courses);
    const categorySelect = document.getElementById('category_select');
    const courseSelect = document.getElementById('course_select');
    
    // Nigeria States and LGAs Data
    const nigeriaData = {
        "Abia": ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North", "Isiala Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma", "Ugwunagbo", "Ukwa East", "Ukwa West", "Umuahia North", "Umuahia South", "Umu Nneochi"],
        "Adamawa": ["Demsa", "Fufure", "Ganye", "Gayuk", "Gombi", "Grie", "Hong", "Jada", "Lamurde", "Madagali", "Maiha", "Mayo Belwa", "Michika", "Mubi North", "Mubi South", "Numan", "Shelleng", "Song", "Toungo", "Yola North", "Yola South"],
        "Akwa Ibom": ["Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Etinan", "Ibeno", "Ibesikpo Asutan", "Ibiono-Ibom", "Ika", "Ikono", "Ikot Abasi", "Ikot Ekpene", "Ini", "Itu", "Mbo", "Mkpat-Enin", "Nsit-Atai", "Nsit-Ibom", "Nsit-Ubium", "Obot Akara", "Okobo", "Onna", "Oron", "Oruk Anam", "Udung-Uko", "Ukanafun", "Uruan", "Urue-Offong/Oruko", "Uyo"],
        "Anambra": ["Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum", "Dunukofia", "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North", "Nnewi South", "Ogbaru", "Onitsha North", "Onitsha South", "Orumba North", "Orumba South", "Oyi"],
        "Bauchi": ["Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade", "Itas/Gadau", "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro", "Warji", "Zaki"],
        "Bayelsa": ["Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw", "Yenagoa"],
        "Benue": ["Ado", "Agatu", "Apa", "Buruku", "Gboko", "Guma", "Gwer East", "Gwer West", "Katsina-Ala", "Konshisha", "Kwande", "Logo", "Makurdi", "Obi", "Ogbadibo", "Ohimini", "Oju", "Okpokwu", "Otukpo", "Tarka", "Ukum", "Ushongo", "Vandeikya"],
        "Borno": ["Abadam", "Askira/Uba", "Bama", "Bayo", "Biu", "Chibok", "Damboa", "Dikwa", "Gubio", "Guzamala", "Gwoza", "Hawul", "Jere", "Kaga", "Kala/Balge", "Konduga", "Kukawa", "Kwaya Kusar", "Mafa", "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"],
        "Cross River": ["Abi", "Akamkpa", "Akpabuyo", "Bakassi", "Bekwarra", "Biase", "Boki", "Calabar Municipal", "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Obudu", "Odukpani", "Ogoja", "Yakuur", "Yala"],
        "Delta": ["Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ethiope East", "Ethiope West", "Ika North East", "Ika South", "Isoko North", "Isoko South", "Ndokwa East", "Ndokwa West", "Okpe", "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North", "Ughelli South", "Ukwuani", "Uvwie", "Warri North", "Warri South", "Warri South West"],
        "Ebonyi": ["Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo", "Ishielu", "Ivo", "Izzi", "Ohaozara", "Ohaukwu", "Onicha"],
        "Edo": ["Akoko-Edo", "Egor", "Esan Central", "Esan North-East", "Esan South-East", "Esan West", "Etsako Central", "Etsako East", "Etsako West", "Igueben", "Ikpoba Okha", "Oredo", "Orhionmwon", "Ovia North-East", "Ovia South-West", "Owan East", "Owan West", "Uhunmwonde"],
        "Ekiti": ["Ado Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Emure", "Gbonyin", "Ido Osi", "Ijero", "Ikere", "Ikole", "Ilejemeje", "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"],
        "Enugu": ["Aninri", "Awgu", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo Etiti", "Igbo Eze North", "Igbo Eze South", "Isi Uzo", "Nkanu East", "Nkanu West", "Nsukka", "Oji River", "Udenu", "Udi", "Uzo Uwani"],
        "FCT": ["Abaji", "Bwari", "Gwagwalada", "Kuje", "Kwali", "Municipal Area Council"],
        "Gombe": ["Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada", "Shongom", "Yamaltu/Deba"],
        "Imo": ["Aboh Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South", "Ihitte/Uboma", "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor Okpala", "Njaba", "Nkwerre", "Nwangele", "Obowo", "Oguta", "Ohaji/Egbema", "Okigwe", "Orlu", "Orsu", "Oru East", "Oru West", "Owerri Municipal", "Owerri North", "Owerri West"],
        "Jigawa": ["Auyo", "Babura", "Biriniwa", "Birnin Kudu", "Buji", "Dutse", "Gagarawa", "Garki", "Gumel", "Guri", "Gwaram", "Gwiwa", "Hadejia", "Jahun", "Kafin Hausa", "Kaugama", "Kazaure", "Kiri Kasama", "Kiyawa", "Maigatari", "Malam Madori", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura", "Yankwashi"],
        "Kaduna": ["Birnin Gwari", "Chikun", "Giwa", "Igabi", "Ikara", "Jaba", "Jema'a", "Kachia", "Kaduna North", "Kaduna South", "Kagarko", "Kajuru", "Kaura", "Kauru", "Kubau", "Kudan", "Lere", "Makarfi", "Sabon Gari", "Sanga", "Soba", "Zangon Kataf", "Zaria"],
        "Kano": ["Ajingi", "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Dawakin Kudu", "Dawakin Tofa", "Doguwa", "Fagge", "Gabasawa", "Garko", "Garun Mallam", "Gaya", "Gezawa", "Gwale", "Gwarzo", "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Kura", "Madobi", "Makoda", "Minjibir", "Nasarawa", "Rano", "Rimin Gado", "Rogo", "Shanono", "Sumaila", "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo", "Warawa", "Wudil"],
        "Katsina": ["Bakori", "Batagarawa", "Batsari", "Baure", "Bindawa", "Charanchi", "Dandume", "Danja", "Dan Musa", "Daura", "Dutsi", "Dutsin Ma", "Faskari", "Funtua", "Ingawa", "Jibia", "Kafur", "Kaita", "Kankara", "Kankia", "Katsina", "Kurfi", "Kusada", "Mai'Adua", "Malumfashi", "Mani", "Mashi", "Matazu", "Musawa", "Rimi", "Sabuwa", "Safana", "Sandamu", "Zango"],
        "Kebbi": ["Aleiro", "Arewa Dandi", "Argungu", "Augie", "Bagudo", "Birnin Kebbi", "Bunza", "Dandi", "Fakai", "Gwandu", "Jega", "Kalgo", "Koko/Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru", "Wasagu/Danko", "Yauri", "Zuru"],
        "Kogi": ["Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela Odolu", "Ijumu", "Kabba/Bunu", "Kogi", "Lokoja", "Mopa Muro", "Ofu", "Ogori/Magongo", "Okehi", "Okene", "Olamaboro", "Omala", "Yagba East", "Yagba West"],
        "Kwara": ["Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West", "Irepodun", "Isin", "Kaiama", "Moro", "Offa", "Oke Ero", "Oyun", "Pategi"],
        "Lagos": ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti Osa", "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland", "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"],
        "Nasarawa": ["Akwanga", "Awe", "Doma", "Karu", "Keana", "Keffi", "Kokona", "Lafia", "Nasarawa", "Nasarawa Egon", "Obi", "Toto", "Wamba"],
        "Niger": ["Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha", "Kontagora", "Lapai", "Lavun", "Magama", "Mariga", "Mashegu", "Mokwa", "Muya", "Pailoro", "Rafi", "Rijau", "Shiroro", "Suleja", "Tafa", "Wushishi"],
        "Ogun": ["Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Egbado North", "Egbado South", "Ewekoro", "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon", "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu"],
        "Ondo": ["Akoko North-East", "Akoko North-West", "Akoko South-East", "Akoko South-West", "Akure North", "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile Oluji/Okeigbo", "Irele", "Odigbo", "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"],
        "Osun": ["Atakunmosa East", "Atakunmosa West", "Ayedaade", "Ayedire", "Boluwaduro", "Boripe", "Ede North", "Ede South", "Egbedore", "Ejigbo", "Ife Central", "Ife East", "Ife North", "Ife South", "Ifedayo", "Ifelodun", "Ila", "Ilesa East", "Ilesa West", "Irepodun", "Irewole", "Isokan", "Iwo", "Obokun", "Odo Otin", "Ola Oluwa", "Olorunda", "Oriade", "Orolu", "Osogbo"],
        "Oyo": ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East", "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East", "Ibarapa North", "Ido", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu", "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope", "Ori Ire", "Oyo East", "Oyo West", "Saki East", "Saki West", "Surulere"],
        "Plateau": ["Barkin Ladi", "Bassa", "Bokkos", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke", "Langtang North", "Langtang South", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam", "Wase"],
        "Rivers": ["Abua/Odual", "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Asari-Toru", "Bonny", "Degema", "Eleme", "Emohua", "Etche", "Gokana", "Ikwerre", "Khana", "Obio/Akpor", "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omuma", "Opobo/Nkoro", "Oyigbo", "Port Harcourt", "Tai"],
        "Sokoto": ["Binji", "Bodinga", "Dange Shuni", "Gada", "Goronyo", "Gudu", "Gwadabawa", "Illela", "Isa", "Kebbe", "Kware", "Rabah", "Sabon Birni", "Shagari", "Silame", "Sokoto North", "Sokoto South", "Tambuwal", "Tangaza", "Tureta", "Wamakko", "Wurno", "Yabo"],
        "Taraba": ["Ardo Kola", "Bali", "Donga", "Gashaka", "Gassol", "Ibi", "Jalingo", "Karim Lamido", "Kurmi", "Lau", "Sardauna", "Takum", "Ussa", "Wukari", "Yorro", "Zing"],
        "Yobe": ["Bade", "Bursari", "Damaturu", "Fika", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa", "Machina", "Nangere", "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yusufari"],
        "Zamfara": ["Anka", "Bakura", "Birnin Magaji/Kiyaw", "Bukkuyum", "Bungudu", "Chafe", "Gummi", "Gusau", "Kaura Namoda", "Maradun", "Maru", "Shinkafi", "Talata Mafara", "Zurmi"]
    };

    const countrySelect = document.getElementById('country_select');
    const countryInput = document.getElementById('country_input');
    const finalCountry = document.getElementById('final_country');

    const nigeriaAddressContainer = document.getElementById('nigeria_address_container');
    const otherAddressContainer = document.getElementById('other_address_container');
    
    const stateSelect = document.getElementById('state_select');
    const lgaSelect = document.getElementById('lga_select');
    const stateInput = document.getElementById('state_input');
    const lgaInput = document.getElementById('lga_input');
    
    const finalState = document.getElementById('final_state');
    const finalLga = document.getElementById('final_lga');

    // Populate States
    for (const state in nigeriaData) {
        const option = document.createElement('option');
        option.value = state;
        option.textContent = state;
        stateSelect.appendChild(option);
    }

    // Handle Country Change
    countrySelect.addEventListener('change', function() {
        const isNigeria = this.value === 'Nigeria';
        
        if (isNigeria) {
            // Logic for Nigeria
            countryInput.classList.add('d-none');
            countryInput.required = false;
            finalCountry.value = 'Nigeria';

            nigeriaAddressContainer.classList.remove('d-none');
            otherAddressContainer.classList.add('d-none');
            
            stateSelect.required = true;
            lgaSelect.required = true;
            stateInput.required = false;
            lgaInput.required = false;
        } else {
            // Logic for Others
            countryInput.classList.remove('d-none');
            countryInput.required = true;
            countryInput.focus();
            finalCountry.value = countryInput.value; // Initialize with current input (likely empty)

            nigeriaAddressContainer.classList.add('d-none');
            otherAddressContainer.classList.remove('d-none');
            
            stateSelect.required = false;
            lgaSelect.required = false;
            stateInput.required = true;
            lgaInput.required = true;
        }
    });

    // Handle Country Input (for Others)
    countryInput.addEventListener('input', function() {
        finalCountry.value = this.value;
    });

    // Handle State Change (Nigeria)
    stateSelect.addEventListener('change', function() {
        const selectedState = this.value;
        lgaSelect.innerHTML = '<option value="">{{ __('Select LGA') }}</option>';
        finalState.value = selectedState;
        
        if (selectedState && nigeriaData[selectedState]) {
            nigeriaData[selectedState].forEach(lga => {
                const option = document.createElement('option');
                option.value = lga;
                option.textContent = lga;
                lgaSelect.appendChild(option);
            });
        }
    });
    
    // Handle LGA Change (Nigeria)
    lgaSelect.addEventListener('change', function() {
        finalLga.value = this.value;
    });
    
    // Handle Inputs (Others)
    stateInput.addEventListener('input', function() {
        finalState.value = this.value;
    });
    
    lgaInput.addEventListener('input', function() {
        finalLga.value = this.value;
    });

    categorySelect.addEventListener('change', function() {
        const selectedCategory = this.value;
        courseSelect.innerHTML = '<option value="">{{ __('Select Course') }}</option>';
        courseSelect.disabled = true;

        if (selectedCategory) {
            const filteredCourses = courses.filter(course => course.category === selectedCategory);
            filteredCourses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = course.course_name;
                courseSelect.appendChild(option);
            });
            courseSelect.disabled = false;
        }
        
        // Reset course details
        document.getElementById('course_details_container').classList.add('d-none');
    });

    courseSelect.addEventListener('change', function() {
        const selectedCourseId = this.value;
        const detailsContainer = document.getElementById('course_details_container');
        const displayFee = document.getElementById('display_fee');
        const displayDuration = document.getElementById('display_duration');

        if (selectedCourseId) {
            const course = courses.find(c => c.id == selectedCourseId);
            if (course) {
                displayFee.textContent = '₦' + parseFloat(course.fee).toLocaleString(undefined, {minimumFractionDigits: 2});
                displayDuration.textContent = course.duration;
                detailsContainer.classList.remove('d-none');
            }
        } else {
            detailsContainer.classList.add('d-none');
        }
    });

    // Qualification Toggle Logic
    const qualificationSelect = document.getElementById('highest_qualification');
    const ssceContainer = document.getElementById('ssce_container');
    const degreeContainer = document.getElementById('degree_container');
    
    // Inputs to toggle 'required' attribute
    const ssceInputs = [
        document.getElementById('ssce_type'),
        document.getElementById('ssce_year'),
        document.getElementById('ssce_exam_number')
    ];
    const degreeInputs = [
        document.getElementById('degree_type'),
        document.getElementById('degree_institution'),
        document.getElementById('degree_year'),
        document.getElementById('degree_class')
    ];

    function toggleQualificationFields() {
        const value = qualificationSelect.value;
        
        // Reset Logic
        ssceContainer.classList.add('d-none');
        degreeContainer.classList.add('d-none');
        
        ssceInputs.forEach(input => input.required = false);
        degreeInputs.forEach(input => input.required = false);

        if (value === 'SSCE') {
            ssceContainer.classList.remove('d-none');
            ssceInputs.forEach(input => input.required = true);
        } else if (value === 'Degree') {
            degreeContainer.classList.remove('d-none');
            degreeInputs.forEach(input => input.required = true);
        }
    }

    qualificationSelect.addEventListener('change', toggleQualificationFields);
    
    // Run on load in case of validation error return
    toggleQualificationFields();

    // Additional Certifications Logic
    const certsContainer = document.getElementById('additional_certs_container');
    const addCertBtn = document.getElementById('add_cert_btn');
    const MAX_CERTS = 5;
    let certCount = 0;

    function createCertRow(index) {
        const row = document.createElement('div');
        row.className = 'row mb-3 cert-row align-items-end';
        row.innerHTML = `
            <div class="col-md-4">
                <label class="form-label small text-muted">{{ __('Certification Name') }}</label>
                <input type="text" name="additional_certifications[${index}][name]" class="form-control" required placeholder="e.g. PMP, CISCO">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">{{ __('Institution') }}</label>
                <input type="text" name="additional_certifications[${index}][institution]" class="form-control" required placeholder="Issuing Body">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">{{ __('Year') }}</label>
                <input type="number" name="additional_certifications[${index}][year]" class="form-control" required min="1980" max="{{ date('Y') }}" placeholder="Year">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-cert-btn">
                    <span class="d-none d-md-inline">&times;</span><span class="d-md-none">Remove</span>
                </button>
            </div>
        `;
        
        row.querySelector('.remove-cert-btn').addEventListener('click', function() {
            row.remove();
            certCount--;
            addCertBtn.disabled = false;
        });

        return row;
    }

    addCertBtn.addEventListener('click', function() {
        if (certCount < MAX_CERTS) {
            certsContainer.appendChild(createCertRow(certCount + Date.now())); // Unique index using timestamp
            certCount++;
            if (certCount >= MAX_CERTS) {
                this.disabled = true;
            }
        }
    });
</script>
@endsection
