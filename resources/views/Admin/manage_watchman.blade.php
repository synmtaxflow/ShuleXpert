@if($user_type == 'Admin')
@include('includes.Admin_nav')
@elseif($user_type == 'Staff')
@include('includes.staff_nav')
@else
@include('includes.teacher_nav')
@endif
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    body, .content, .card, .btn, .form-control, .table {
        font-family: "Century Gothic", Arial, sans-serif;
    }
    .card, .btn, .form-control, div { border-radius: 0 !important; }
    .bg-primary-custom { background-color: #940000 !important; }
    .btn-primary-custom { background-color: #940000; border-color: #940000; color: #fff; }
    .btn-primary-custom:hover { background-color: #b30000; border-color: #b30000; color: #fff; }
    .section-title { font-weight: 600; margin-bottom: 12px; }
    .form-loading {
        display: none;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border: 1px solid rgba(148, 0, 0, 0.25);
        background: rgba(148, 0, 0, 0.05);
        margin-bottom: 12px;
    }
    .form-progress {
        position: relative;
        flex: 1;
        height: 8px;
        background: #f0f0f0;
        border-radius: 4px;
        overflow: hidden;
    }
    .form-progress::after {
        content: "";
        position: absolute;
        left: -40%;
        width: 40%;
        height: 100%;
        background: #940000;
        animation: progressSlide 1.1s linear infinite;
    }
    @keyframes progressSlide {
        0% { left: -40%; }
        100% { left: 100%; }
    }
</style>

<div class="content mt-3">
    <div class="card">
        <div class="card-header bg-primary-custom text-white">
            <strong>Watchman Management</strong>
        </div>
        <div class="card-body">
            <div class="section-title">Register Watchman</div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-loading" id="watchmanLoading">
                <span><i class="fa fa-spinner fa-spin text-primary-custom"></i> Saving...</span>
                <div class="form-progress"></div>
            </div>

            <form method="POST" action="{{ route('save_watchman') }}" class="mb-4" id="watchmanForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', '255') }}" placeholder="255612345678" required>
                        <small class="text-muted">Username will be this phone number.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Email (optional)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@email.com">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fa fa-save"></i> Save Watchman
                    </button>
                </div>
            </form>

            <div class="section-title">Registered Watchmen</div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary-custom text-white">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($watchmen as $index => $watchman)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $watchman->first_name }} {{ $watchman->last_name }}</td>
                                <td>{{ $watchman->phone_number }}</td>
                                <td>{{ $watchman->email ?? '-' }}</td>
                                <td>{{ $watchman->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary-custom edit-watchman-btn" data-watchman-id="{{ $watchman->id }}">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-watchman-btn" data-watchman-id="{{ $watchman->id }}" data-watchman-name="{{ $watchman->first_name }} {{ $watchman->last_name }}">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No watchmen registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Watchman Modal -->
<div class="modal fade" id="editWatchmanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:0;">
            <div class="modal-header bg-primary-custom text-white" style="border-radius:0;">
                <h5 class="modal-title">Edit Watchman</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editWatchmanForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_watchman_id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="edit_first_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="edit_last_name" class="form-control" required>
                            <small class="text-muted">Password will be updated to this last name.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone_number" id="edit_phone_number" class="form-control" required>
                            <small class="text-muted">Username will be this phone number.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email (optional)</label>
                            <input type="email" name="email" id="edit_email" class="form-control" placeholder="example@email.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="edit_status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom" id="updateWatchmanBtn">
                        <i class="fa fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const watchmanForm = document.getElementById('watchmanForm');
    const watchmanLoading = document.getElementById('watchmanLoading');
    if (watchmanForm) {
        watchmanForm.addEventListener('submit', () => {
            if (watchmanLoading) {
                watchmanLoading.style.display = 'flex';
            }
        });
    }

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    document.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.edit-watchman-btn');
        if (editBtn) {
            const id = editBtn.getAttribute('data-watchman-id');
            fetch(`{{ route('get_watchman', ':id') }}`.replace(':id', id), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to load watchman.' });
                    } else {
                        alert(data.message || 'Failed to load watchman.');
                    }
                    return;
                }
                const w = data.watchman;
                document.getElementById('edit_watchman_id').value = w.id;
                document.getElementById('edit_first_name').value = w.first_name || '';
                document.getElementById('edit_last_name').value = w.last_name || '';
                document.getElementById('edit_phone_number').value = w.phone_number || '';
                document.getElementById('edit_email').value = w.email || '';
                document.getElementById('edit_status').value = w.status || 'Active';
                $('#editWatchmanModal').modal('show');
            })
            .catch(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load watchman.' });
                } else {
                    alert('Failed to load watchman.');
                }
            });
        }

        const deleteBtn = e.target.closest('.delete-watchman-btn');
        if (deleteBtn) {
            const id = deleteBtn.getAttribute('data-watchman-id');
            const name = deleteBtn.getAttribute('data-watchman-name') || 'this watchman';

            const proceed = () => {
                fetch(`{{ route('delete_watchman', ':id') }}`.replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'success', title: 'Deleted', text: data.message || 'Deleted successfully.' })
                                .then(() => location.reload());
                        } else {
                            alert(data.message || 'Deleted successfully.');
                            location.reload();
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Delete failed.' });
                        } else {
                            alert(data.message || 'Delete failed.');
                        }
                    }
                })
                .catch(() => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Failed', text: 'Delete failed.' });
                    } else {
                        alert('Delete failed.');
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Delete watchman?',
                    text: `Delete ${name}?`,
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#d33'
                }).then(r => { if (r.isConfirmed) proceed(); });
            } else {
                if (confirm(`Delete ${name}?`)) proceed();
            }
        }
    });

    const editWatchmanForm = document.getElementById('editWatchmanForm');
    if (editWatchmanForm) {
        editWatchmanForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = document.getElementById('updateWatchmanBtn');
            const original = btn ? btn.innerHTML : null;
            if (btn) btn.disabled = true;

            const formData = new FormData(editWatchmanForm);
            formData.append('_token', getCsrfToken());

            fetch(`{{ route('update_watchman') }}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));
                return { ok: res.ok, status: res.status, data };
            })
            .then(({ ok, status, data }) => {
                if (btn) {
                    btn.disabled = false;
                    if (original !== null) btn.innerHTML = original;
                }

                if (ok && data.success) {
                    $('#editWatchmanModal').modal('hide');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Updated', text: data.message || 'Watchman updated.' })
                            .then(() => location.reload());
                    } else {
                        alert(data.message || 'Watchman updated.');
                        location.reload();
                    }
                    return;
                }

                let msg = data.message || 'Failed to update watchman.';
                if (status === 422 && data.errors) {
                    msg = Object.values(data.errors).map(v => Array.isArray(v) ? v[0] : v).join('\n');
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Failed', text: msg });
                } else {
                    alert(msg);
                }
            })
            .catch(() => {
                if (btn) {
                    btn.disabled = false;
                    if (original !== null) btn.innerHTML = original;
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Failed', text: 'Failed to update watchman.' });
                } else {
                    alert('Failed to update watchman.');
                }
            });
        });
    }
</script>
