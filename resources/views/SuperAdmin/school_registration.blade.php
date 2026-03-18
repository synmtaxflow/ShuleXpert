@extends('includes.superadmin_nav')

@section('content')
<style>
    :root { --brand: #940000; }
    .brand-text { color: var(--brand) !important; }
    .card-brand { border-top: 4px solid var(--brand); }
    .form-label { font-weight: 600; }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 .2rem rgba(148,0,0,.15) !important;
    }
    .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--brand); }
    .with-icon { padding-left: 42px; }
    .help { font-size: .85rem; color: #6c757d; }
    .is-valid + .valid-feedback { display: block; }
    .is-invalid + .invalid-feedback { display: block; }
    .required::after { content: " *"; color: var(--brand); font-weight: 700; }
</style>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>There were some errors in your form:</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm card-brand">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 brand-text"><i class="fa fa-building mr-2"></i>School Registration</h5>
                    <a href="{{ route('superadmin.schools.index') }}" class="btn btn-sm btn-outline-primary-custom">Registered Schools</a>
                </div>
                <div class="card-body">

                    <form id="schoolForm" method="POST" action="{{ route('save_school') }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label required" for="school_name">School Name</label>
                                <div class="position-relative">
                                    <i class="fa fa-graduation-cap input-icon"></i>
                                    <input type="text" id="school_name" name="school_name" class="form-control with-icon @error('school_name') is-invalid @enderror" value="{{ old('school_name') }}" placeholder="e.g., Kilimanjaro Primary School" required>
                                    @error('school_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="registration_number">Registration Number</label>
                                <div class="position-relative">
                                    <i class="fa fa-hashtag input-icon"></i>
                                    <input type="text" id="registration_number" name="registration_number" class="form-control with-icon @error('registration_number') is-invalid @enderror" value="{{ old('registration_number') }}" placeholder="e.g., REG-12345">
                                    @error('registration_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required" for="school_type">School Type</label>
                                <select id="school_type" name="school_type" class="form-select @error('school_type') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('school_type') ? '' : 'selected' }}>Select type</option>
                                    <option value="Primary" {{ old('school_type') == 'Primary' ? 'selected' : '' }}>Primary</option>
                                    <option value="Secondary" {{ old('school_type') == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                                </select>
                                @error('school_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required" for="ownership">Ownership</label>
                                <select id="ownership" name="ownership" class="form-select @error('ownership') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('ownership') ? '' : 'selected' }}>Select ownership</option>
                                    <option value="Public" {{ old('ownership') == 'Public' ? 'selected' : '' }}>Public</option>
                                    <option value="Private" {{ old('ownership') == 'Private' ? 'selected' : '' }}>Private</option>
                                </select>
                                @error('ownership')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="environment">Environment</label>
                                <select id="environment" name="environment" class="form-select @error('environment') is-invalid @enderror">
                                    <option value="Demo" {{ old('environment', 'Demo') == 'Demo' ? 'selected' : '' }}>Demo</option>
                                    <option value="Live" {{ old('environment') == 'Live' ? 'selected' : '' }}>Live</option>
                                </select>
                                @error('environment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="help">If not set, system will treat it as Demo.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required" for="region">Region</label>
                                <input type="text" id="region" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region') }}" placeholder="e.g., Kilimanjaro" required>
                                @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required" for="district">District</label>
                                <input type="text" id="district" name="district" class="form-control @error('district') is-invalid @enderror" value="{{ old('district') }}" placeholder="e.g., Moshi" required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="ward">Ward</label>
                                <input type="text" id="ward" name="ward" class="form-control" value="{{ old('ward') }}" placeholder="e.g., Rau">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="village">Village</label>
                                <input type="text" id="village" name="village" class="form-control" value="{{ old('village') }}" placeholder="e.g., Rau Village">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}" placeholder="P.O. Box 123, Moshi">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="info@school.tz">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="e.g., +255 712 000 000">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="established_year">Established Year</label>
                                <input type="number" id="established_year" name="established_year" class="form-control @error('established_year') is-invalid @enderror" min="1900" max="{{ date('Y') }}" value="{{ old('established_year') }}" placeholder="e.g., 1998">
                                @error('established_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label" for="school_logo">School Logo</label>
                                <input type="file" id="school_logo" name="school_logo" class="form-control @error('school_logo') is-invalid @enderror" accept="image/*">
                                <div class="help">Optional. JPG/PNG only, max 2MB.</div>
                                @error('school_logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label required" for="status">Status</label>
                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="two_factor_enabled" name="two_factor_enabled" {{ old('two_factor_enabled') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="two_factor_enabled">
                                        Enable Two Factor Authentication (2FA)
                                    </label>
                                    <div class="help">If enabled, users will be required to login with OTP via SMS (parents and students excluded).</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="agree" required>
                                <label class="form-check-label" for="agree">I confirm the details are correct</label>
                                <div class="invalid-feedback">Please confirm before submitting.</div>
                            </div>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fa fa-refresh mr-1"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fa fa-save mr-1"></i>Save School
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    (function() {
        const form = document.getElementById('schoolForm');
        if (!form) return;

        const validators = {
            school_name: v => v.trim().length > 2,
            school_type: v => !!v,
            ownership: v => !!v,
            region: v => v.trim().length > 1,
            district: v => v.trim().length > 1,
            email: v => !v || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),
            phone: v => !v || /^[+0-9\s-]{7,20}$/.test(v),
            established_year: v => !v || (+v >= 1900 && +v <= new Date().getFullYear()),
            agree: v => !!v,
        };

        const setValidity = (el, valid) => {
            el.classList.remove(valid ? 'is-invalid' : 'is-valid');
            el.classList.add(valid ? 'is-valid' : 'is-invalid');
        };

        form.addEventListener('input', e => {
            const t = e.target;
            if (!t.name || !(t.name in validators)) return;
            const valid = validators[t.name](t.type === 'checkbox' ? (t.checked ? '1' : '') : t.value || '');
            setValidity(t, valid);
        });

        form.addEventListener('submit', e => {
            let ok = true;
            Array.from(form.elements).forEach(el => {
                if (!el.name || !(el.name in validators)) return;
                const valid = validators[el.name](el.type === 'checkbox' ? (el.checked ? '1' : '') : el.value || '');
                setValidity(el, valid);
                if (!valid) ok = false;
            });
            if (!ok) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    })();
</script>
@endsection
