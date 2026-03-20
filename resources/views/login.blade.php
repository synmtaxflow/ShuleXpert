<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ShuleXpert - Settings</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom Color Override -->
    <style>
        :root { --brand: #940000; }
        .brand-text { color: var(--brand) !important; }
        .brand-bg { background-color: var(--brand) !important; }
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
        .btn-brand { background: var(--brand); color: #fff; border-color: var(--brand); }
        .btn-brand:hover { background: #7a0000; border-color: #7a0000; }
        .required::after { content: " *"; color: var(--brand); font-weight: 700; }
        .fade-in { animation: fadeIn .5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

        .book-open-in { animation: bookOpenIn .55s ease-in-out both; transform-origin: left center; }
        .book-open-out { animation: bookOpenOut .55s ease-in-out both; transform-origin: left center; }
        @keyframes bookOpenIn {
            from { opacity: 0; transform: perspective(900px) rotateY(-85deg); }
            to { opacity: 1; transform: perspective(900px) rotateY(0deg); }
        }
        @keyframes bookOpenOut {
            from { opacity: 1; transform: perspective(900px) rotateY(0deg); }
            to { opacity: 0; transform: perspective(900px) rotateY(85deg); }
        }
    </style>
</head>

<body>
@include('includes.web_nav')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="card shadow-sm card-brand fade-in">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 brand-text"><i class="bi bi-building me-2"></i>School Member Login</h5>
                        <span class="text-muted small">ShuleXpert</span>
                    </div>
                    <div class="card-body">
                        <div id="loginAlert"></div>
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
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


<form id="schoolForm" method="POST" action="{{ route('auth') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        {{-- Username --}}
        <div class="col-md-12">
            <label class="form-label required" for="school_name">username</label>
            <div class="position-relative">
                <i class="bi bi-mortarboard input-icon"></i>
                <input type="text" id="school_name" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="username" value="{{ old('username') }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Pasword --}}
        <div class="col-md-12">
            <label class="form-label" for="registration_number">Password</label>
            <div class="position-relative">
                <i class="bi bi-hash input-icon"></i>
                <input type="password" id="registration_number" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Remember Me --}}
        <div class="col-md-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                <label class="form-check-label" for="remember_me">
                    Remember Me
                </label>
            </div>
        </div>

        <div class="col-md-12 d-grid gap-2">
            <button type="submit" class="btn btn-brand">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </button>
        </div>
    </div>
</form>

<div id="otpWrap" class="mt-4" style="display:none;">
    <div class="border rounded" style="border-color: rgba(148,0,0,.2) !important; overflow:hidden; background:#ffffff;">
        <div class="d-flex align-items-center justify-content-between" style="background:#940000;padding:12px 14px;">
            <h6 class="mb-0" style="color:#fff;"><i class="bi bi-shield-lock me-2" style="color:#fff;"></i>OTP Verification</h6>
            <span class="small" style="color:rgba(255,255,255,.92);">ShuleXpert</span>
        </div>
        <div class="p-3">
        <div class="help mb-2" id="otpTitle" style="color:#2f2f2f;">ENTER 6 DIGITS CODE WE SENT TO YOUR PHONE</div>
        <input type="hidden" id="otpToken" value="">
        <input type="hidden" id="otpMaskedPhone" value="">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label required" for="otpBox0" style="color:#2f2f2f;">OTP</label>
                <div class="d-flex" style="gap: 10px;">
                    <input type="text" id="otpBox0" class="form-control text-center" style="max-width: 52px;" inputmode="numeric" maxlength="1" autocomplete="one-time-code">
                    <input type="text" id="otpBox1" class="form-control text-center" style="max-width: 52px;" inputmode="numeric" maxlength="1">
                    <input type="text" id="otpBox2" class="form-control text-center" style="max-width: 52px;" inputmode="numeric" maxlength="1">
                    <input type="text" id="otpBox3" class="form-control text-center" style="max-width: 52px;" inputmode="numeric" maxlength="1">
                    <input type="text" id="otpBox4" class="form-control text-center" style="max-width: 52px;" inputmode="numeric" maxlength="1">
                    <input type="text" id="otpBox5" class="form-control text-center" style="max-width: 52px;" inputmode="numeric" maxlength="1">
                </div>
                <div class="help" id="otpHint" style="color:#dc3545;"></div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div id="otpStatus" class="small text-muted">Waiting OTP...</div>
                    <div class="small text-muted" id="otpTimer"></div>
                </div>
                <div class="mt-2 d-flex" style="gap: 10px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBackLogin">Back Login</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnResendOtp">Resend OTP</button>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
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
                remember_me: v => true,
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

    <script>
        (function() {
            const form = document.getElementById('schoolForm');
            const otpWrap = document.getElementById('otpWrap');
            const otpToken = document.getElementById('otpToken');
            const otpMaskedPhone = document.getElementById('otpMaskedPhone');
            const otpStatus = document.getElementById('otpStatus');
            const otpHint = document.getElementById('otpHint');
            const otpTitle = document.getElementById('otpTitle');
            const otpTimer = document.getElementById('otpTimer');
            const btnBackLogin = document.getElementById('btnBackLogin');
            const btnResendOtp = document.getElementById('btnResendOtp');

            const boxes = [0,1,2,3,4,5].map(i => document.getElementById('otpBox' + i));

            if (!form || !otpWrap || !otpToken || boxes.some(b => !b)) return;

            let verifying = false;
            let lastSentOtp = '';
            let expiresAt = 0;
            let timerId = null;

            function startTimer(seconds) {
                expiresAt = Date.now() + (Math.max(0, seconds || 0) * 1000);
                if (timerId) clearInterval(timerId);
                timerId = setInterval(() => {
                    const ms = expiresAt - Date.now();
                    const s = Math.max(0, Math.ceil(ms / 1000));
                    if (otpTimer) {
                        const mm = String(Math.floor(s / 60)).padStart(2, '0');
                        const ss = String(s % 60).padStart(2, '0');
                        otpTimer.textContent = s > 0 ? ('Expires in ' + mm + ':' + ss) : 'OTP expired';
                    }
                    if (s <= 0) {
                        if (timerId) clearInterval(timerId);
                        timerId = null;
                    }
                }, 500);
            }

            function showOtp(token, maskedPhone, expiresIn) {
                otpToken.value = token || '';
                otpMaskedPhone.value = maskedPhone || '';

                form.classList.remove('book-open-in');
                form.classList.add('book-open-out');
                setTimeout(() => {
                    form.style.display = 'none';
                    otpWrap.style.display = 'block';
                    otpWrap.classList.remove('book-open-out');
                    otpWrap.classList.add('book-open-in');
                }, 250);

                boxes.forEach(b => b.value = '');
                otpStatus.textContent = 'Waiting OTP...';
                otpHint.textContent = '';

                if (otpTitle) {
                    const tail = maskedPhone ? (' ' + maskedPhone) : '';
                    otpTitle.textContent = 'ENTER 6 DIGITS CODE WE SENT TO YOUR PHONE' + tail;
                }

                setTimeout(() => boxes[0].focus(), 50);
                startTimer(expiresIn || 600);
            }

            function backToLogin() {
                otpWrap.classList.remove('book-open-in');
                otpWrap.classList.add('book-open-out');
                setTimeout(() => {
                    otpWrap.style.display = 'none';
                    form.style.display = 'block';
                    form.classList.remove('book-open-out');
                    form.classList.add('book-open-in');
                }, 250);
            }

            function setStatus(text, cls) {
                // Avoid bootstrap text-* classes for success because they use !important and can override inline color
                if (cls === 'text-success') {
                    otpStatus.className = 'small';
                } else {
                    otpStatus.className = 'small ' + (cls || 'text-muted');
                }
                otpStatus.textContent = text;

                // Default style
                otpStatus.style.background = '';
                otpStatus.style.color = '';
                otpStatus.style.padding = '';
                otpStatus.style.borderRadius = '';
                otpStatus.style.display = '';

                // Success should be white text on brand background
                if (cls === 'text-success') {
                    otpStatus.style.display = 'inline-block';
                    otpStatus.style.background = '#940000';
                    otpStatus.style.color = '#ffffff';
                    otpStatus.style.padding = '4px 10px';
                    otpStatus.style.borderRadius = '10px';
                }
            }

            async function verifyOtpNow(code) {
                if (verifying) return;
                if (!code || code.length < 4) return;
                if (code === lastSentOtp) return;
                lastSentOtp = code;
                verifying = true;
                setStatus('Verifying OTP...', 'text-primary');
                try {
                    const res = await fetch('{{ route('auth.otp.verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ otp: code, otp_token: otpToken.value })
                    });
                    const data = await res.json();
                    if (data && data.success) {
                        setStatus('Correct OTP. Please wait login..', 'text-success');
                        window.location.href = data.redirect;
                        return;
                    }
                    const msg = (data && data.message) ? data.message : 'OTP verification failed';
                    setStatus(msg, 'text-danger');
                    otpHint.textContent = msg;
                } catch (err) {
                    setStatus('Network error verifying OTP', 'text-danger');
                } finally {
                    verifying = false;
                }
            }

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const loginAlert = document.getElementById('loginAlert');
                const showLoginError = (message) => {
                    if (!loginAlert) return;
                    loginAlert.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${String(message || 'Incorrect username or password!')}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                };

                if (loginAlert) loginAlert.innerHTML = '';

                const fd = new FormData(form);
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Logging in...';
                }

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: fd
                    });

                    const data = await res.json().catch(() => null);
                    if (data && data.requires_otp && data.otp_token) {
                        showOtp(data.otp_token, data.masked_phone || '', data.expires_in || 600);
                        return;
                    }

                    if (data && data.success && data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }

                    const msg = (data && (data.message || (data.errors ? 'Validation failed' : null))) || 'Incorrect username or password!';
                    showLoginError(msg);
                } catch (err) {
                    showLoginError('Network error. Please try again.');
                } finally {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-save me-1"></i>Login';
                    }
                }
            });

            function getCode() {
                return boxes.map(b => (b.value || '').replace(/\D/g, '')).join('');
            }

            function focusBox(i) {
                if (i < 0) i = 0;
                if (i > 5) i = 5;
                boxes[i].focus();
                boxes[i].select();
            }

            boxes.forEach((box, idx) => {
                box.addEventListener('input', () => {
                    box.value = (box.value || '').replace(/\D/g, '').slice(0, 1);
                    if (box.value && idx < 5) focusBox(idx + 1);

                    const code = getCode();
                    if (code.length === 6) {
                        verifyOtpNow(code);
                    } else {
                        setStatus('Waiting OTP...', 'text-muted');
                    }
                });

                box.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !box.value && idx > 0) {
                        focusBox(idx - 1);
                    }
                    if (e.key === 'ArrowLeft' && idx > 0) {
                        e.preventDefault();
                        focusBox(idx - 1);
                    }
                    if (e.key === 'ArrowRight' && idx < 5) {
                        e.preventDefault();
                        focusBox(idx + 1);
                    }
                });

                box.addEventListener('paste', (e) => {
                    const t = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = (t || '').replace(/\D/g, '').slice(0, 6).split('');
                    if (digits.length) {
                        e.preventDefault();
                        digits.forEach((d, i) => { if (boxes[i]) boxes[i].value = d; });
                        const code = getCode();
                        if (code.length === 6) verifyOtpNow(code);
                        else focusBox(Math.min(digits.length, 5));
                    }
                });
            });

            if (btnBackLogin) {
                btnBackLogin.addEventListener('click', backToLogin);
            }

            if (btnResendOtp) {
                btnResendOtp.addEventListener('click', async function() {
                    if (!otpToken.value) return;
                    setStatus('Resending OTP...', 'text-primary');
                    try {
                        const res = await fetch('{{ route('auth.otp.resend') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ otp_token: otpToken.value })
                        });
                        const data = await res.json();
                        if (data && data.success) {
                            const masked = data.masked_phone || otpMaskedPhone.value || '';
                            if (otpTitle) {
                                otpTitle.textContent = 'ENTER 6 DIGITS CODE WE SENT TO YOUR PHONE' + (masked ? (' ' + masked) : '');
                            }
                            boxes.forEach(b => b.value = '');
                            boxes[0].focus();
                            startTimer(data.expires_in || 600);
                            setStatus('OTP resent. Waiting...', 'text-success');
                            otpHint.textContent = '';
                            return;
                        }
                        const msg = (data && data.message) ? data.message : 'Failed to resend OTP';
                        setStatus(msg, 'text-danger');
                    } catch (e) {
                        setStatus('Network error resending OTP', 'text-danger');
                    }
                });
            }
        })();
    </script>
</html>
