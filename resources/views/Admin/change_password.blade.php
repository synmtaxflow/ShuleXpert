@include('includes.Admin_nav')

<div class="content mt-3">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Change Password</h5>
                        <a href="{{ route('AdminDashboard') }}" class="btn btn-sm btn-outline-primary-custom">Back</a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.change_password.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" id="admin_new_password" class="form-control" required>
                                <div class="mt-2">
                                    <div class="progress" style="height: 8px;">
                                        <div id="admin_pw_bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                    </div>
                                    <small id="admin_pw_label" class="text-muted">Strength: 0%</small>
                                </div>
                                <small class="text-muted d-block mt-2">Use at least 8 characters including uppercase, lowercase, number, and special character.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100">Update Password</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const input = document.getElementById('admin_new_password');
    const bar = document.getElementById('admin_pw_bar');
    const label = document.getElementById('admin_pw_label');
    if (!input || !bar || !label) return;

    function calc(pw) {
        let score = 0;
        if (!pw) return 0;
        if (pw.length >= 8) score += 25;
        if (/[a-z]/.test(pw)) score += 15;
        if (/[A-Z]/.test(pw)) score += 15;
        if (/[0-9]/.test(pw)) score += 15;
        if (/[^a-zA-Z0-9]/.test(pw)) score += 15;
        if (pw.length >= 12) score += 15;
        return Math.min(100, score);
    }

    function color(p) {
        if (p < 35) return 'bg-danger';
        if (p < 70) return 'bg-warning';
        return 'bg-success';
    }

    input.addEventListener('input', function() {
        const p = calc(input.value);
        bar.style.width = p + '%';
        bar.className = 'progress-bar ' + color(p);
        label.textContent = 'Strength: ' + p + '%';
    });
})();
</script>
