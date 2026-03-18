@extends('includes.superadmin_nav')

@section('content')
<div class="container-fluid mt-3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <style>
        .school-logo {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid rgba(148, 0, 0, 0.15);
            background: #fff;
        }
        .select2-container--default .select2-selection--single {
            height: 38px;
            border-radius: 0 !important;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>

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

    <div class="card">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Customer Care (Bulk SMS)</h5>
            <div>
                <a href="{{ route('superAdminDashboard') }}" class="btn btn-sm btn-outline-primary-custom">Dashboard</a>
                <a href="{{ route('superadmin.schools.index') }}" class="btn btn-sm btn-outline-primary-custom">Schools</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-5 mb-3">
                    <label class="font-weight-bold">Select School</label>
                    <select id="schoolID" class="form-control">
                        <option value="">-- Select School --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->schoolID }}">{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">You can search inside the list.</small>
                </div>

                <div class="col-lg-3 mb-3">
                    <label class="font-weight-bold">Filter User Type</label>
                    <select id="userType" class="form-control">
                        @foreach($userTypes as $ut)
                            <option value="{{ $ut }}">{{ $ut }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 mb-3">
                    <label class="font-weight-bold">SMS Message</label>
                    <textarea id="smsMessage" class="form-control" rows="3" placeholder="Andika ujumbe wako hapa..."></textarea>
                    <small class="text-muted">Placeholders (optional): {username}, {school}</small>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="selectAllUsers">
                    <label class="custom-control-label font-weight-bold" for="selectAllUsers">Select All</label>
                </div>
                <div>
                    <span class="badge badge-success" id="selectedCount" style="font-size: 1rem;">Receivers: 0</span>
                    <button type="button" class="btn btn-primary-custom btn-sm" id="btnSendSms" disabled>
                        <i class="bi bi-chat-dots"></i> Send SMS
                    </button>
                </div>
            </div>

            <div class="table-responsive" style="max-height: 420px;">
                <table class="table table-sm table-hover table-bordered" id="usersTable">
                    <thead class="bg-light sticky-top">
                        <tr>
                            <th style="width: 40px;"></th>
                            <th style="width: 60px;">Logo</th>
                            <th>School</th>
                            <th>Username</th>
                            <th>User Type</th>
                            <th>Phone</th>
                            <th class="text-center" style="width: 120px;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="usersTbody">
                        <tr>
                            <td colspan="7" class="text-center text-muted">Select a school to load users.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="smsProgressArea" class="mt-4 d-none">
                <hr>
                <div class="d-flex justify-content-between mb-1">
                    <span class="font-weight-bold text-primary-custom">Sending Progress</span>
                    <span id="smsProgressText" class="small">0 / 0</span>
                </div>
                <div class="progress" style="height: 25px; border-radius: 12px; border: 1px solid #ddd;">
                    <div id="smsProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <div id="smsDeliverySummary" class="text-center small mt-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let loadedUsers = [];
    let usersDataTable = null;

    $('#schoolID').select2({
        width: '100%',
        placeholder: '-- Select School --',
        allowClear: true
    });

    function safeText(v) {
        return $('<div>').text(v == null ? '' : String(v)).html();
    }

    function updateSelectedCount() {
        const count = $('.user-checkbox:checked').length;
        $('#selectedCount').text('Receivers: ' + count);
        $('#btnSendSms').prop('disabled', count === 0);
    }

    function initUsersDataTable() {
        if (usersDataTable) {
            usersDataTable.destroy();
            usersDataTable = null;
        }
        usersDataTable = $('#usersTable').DataTable({
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            autoWidth: false,
            columnDefs: [
                { orderable: false, targets: [0, 1, 6] }
            ]
        });
    }

    function resetProgress() {
        $('#smsProgressArea').addClass('d-none');
        $('#smsProgressText').text('0 / 0');
        $('#smsProgressBar').css('width', '0%').text('0%');
        $('#smsDeliverySummary').empty();
    }

    async function loadUsers() {
        const schoolID = $('#schoolID').val();
        const userType = $('#userType').val();

        loadedUsers = [];
        resetProgress();
        $('#selectAllUsers').prop('checked', false);

        if (usersDataTable) {
            usersDataTable.clear().destroy();
            usersDataTable = null;
        }

        $('#usersTbody').html('<tr><td colspan="7" class="text-center text-muted"><div class="spinner-border spinner-border-sm"></div> Loading...</td></tr>');

        if (!schoolID) {
            $('#usersTbody').html('<tr><td colspan="7" class="text-center text-muted">Select a school to load users.</td></tr>');
            updateSelectedCount();
            return;
        }

        try {
            const res = await $.ajax({
                url: '{{ route("superadmin.customer_care.users") }}',
                type: 'GET',
                data: { schoolID: schoolID, user_type: userType }
            });

            if (!res.success) {
                $('#usersTbody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load users.</td></tr>');
                updateSelectedCount();
                return;
            }

            loadedUsers = res.users || [];

            if (loadedUsers.length === 0) {
                $('#usersTbody').html('<tr><td colspan="7" class="text-center text-muted">No users found for this selection.</td></tr>');
                updateSelectedCount();
                return;
            }

            const rowsHtml = loadedUsers.map((u, idx) => {
                const checkboxId = 'u_chk_' + idx + '_' + Math.random().toString(36).substr(2,5);
                const phone = (u.phone || '').toString().trim();
                const hasPhone = phone && !['null','undefined','n/a'].includes(phone.toLowerCase());
                const disabledAttr = hasPhone ? '' : 'disabled="disabled"';
                const statusHtml = hasPhone ? '<span class="status-marker text-muted small">Pending</span>' : '<span class="text-danger small">No Phone</span>';

                const logo = u.school_logo
                    ? `<img src="${safeText(u.school_logo)}" class="school-logo" alt="logo">`
                    : `<div class="school-logo d-inline-flex align-items-center justify-content-center"><i class="fa fa-building" style="color: rgba(148, 0, 0, 0.45);"></i></div>`;

                return `
                    <tr data-school-id="${safeText(u.schoolID)}" data-user-type="${safeText(u.user_type)}" data-username="${safeText(u.username)}" data-phone="${safeText(phone)}">
                        <td class="text-center">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input user-checkbox" id="${checkboxId}" ${disabledAttr}>
                                <label class="custom-control-label" for="${checkboxId}">&nbsp;&nbsp;&nbsp;</label>
                            </div>
                        </td>
                        <td class="text-center">${logo}</td>
                        <td>${safeText(u.school_name)}</td>
                        <td>${safeText(u.username)}</td>
                        <td>${safeText(u.user_type)}</td>
                        <td>${hasPhone ? safeText(phone) : '<span class="text-danger small">No Phone</span>'}</td>
                        <td class="text-center status-col">${statusHtml}</td>
                    </tr>
                `;
            }).join('');

            $('#usersTbody').html(rowsHtml);
            initUsersDataTable();
            updateSelectedCount();

        } catch (e) {
            $('#usersTbody').html('<tr><td colspan="7" class="text-center text-danger">Network error while loading users.</td></tr>');
            updateSelectedCount();
        }
    }

    $('#schoolID').on('change', loadUsers);
    $('#userType').on('change', loadUsers);

    $('#selectAllUsers').on('change', function() {
        $('.user-checkbox:not(:disabled)').prop('checked', $(this).is(':checked'));
        updateSelectedCount();
    });

    $(document).on('change', '.user-checkbox', function() {
        updateSelectedCount();
    });

    $('#btnSendSms').on('click', async function() {
        const selectedRows = $('.user-checkbox:checked').closest('tr');
        if (selectedRows.length === 0) return;

        const msgText = $('#smsMessage').val();
        if (!msgText || msgText.trim().length === 0) {
            Swal.fire({ icon: 'warning', title: 'Message required', text: 'Please type your SMS message.' });
            return;
        }

        const confirmed = await Swal.fire({
            title: 'Send SMS?',
            text: `You are about to send SMS to ${selectedRows.length} users.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#940000',
            confirmButtonText: 'Yes, Send Now'
        });

        if (!confirmed.isConfirmed) return;

        $('#btnSendSms').prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> Sending...');
        $('#schoolID, #userType, #smsMessage, #selectAllUsers').prop('disabled', true);
        $('.user-checkbox').prop('disabled', true);

        $('#smsProgressArea').removeClass('d-none');

        const total = selectedRows.length;
        let delivered = 0;
        let failed = 0;
        let firstErrorMessage = null;

        $('#smsProgressText').text(`0 / ${total}`);
        $('#smsDeliverySummary').empty();

        for (let i = 0; i < total; i++) {
            const row = $(selectedRows[i]);
            const statusCol = row.find('.status-col');
            statusCol.html('<div class="spinner-border spinner-border-sm text-primary" role="status"></div>');

            const payload = {
                schoolID: row.data('school-id'),
                user_type: row.data('user-type'),
                username: row.data('username'),
                phone: row.data('phone'),
                message: msgText,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            try {
                const response = await $.ajax({
                    url: '{{ route("superadmin.customer_care.send_sms") }}',
                    type: 'POST',
                    data: payload
                });

                if (response && response.success) {
                    statusCol.html('<i class="bi bi-check-circle-fill text-success"></i>');
                    delivered++;
                } else {
                    const emsg = (response && (response.error || response.message)) || 'Failed';
                    if (!firstErrorMessage) firstErrorMessage = emsg;
                    statusCol.html('<i class="bi bi-exclamation-circle-fill text-danger" title="'+ safeText(emsg) +'"></i> <span class="small text-danger">'+ safeText(emsg) +'</span>');
                    failed++;
                }
            } catch (err) {
                const errMsg = (err && err.responseJSON && (err.responseJSON.error || err.responseJSON.message))
                    ? (err.responseJSON.error || err.responseJSON.message)
                    : ('HTTP ' + (err.status || '') + ' ' + (err.statusText || 'Request Failed'));
                if (!firstErrorMessage) firstErrorMessage = errMsg;
                statusCol.html('<i class="bi bi-exclamation-circle-fill text-danger" title="'+ safeText(errMsg) +'"></i> <span class="small text-danger">'+ safeText(errMsg) +'</span>');
                failed++;
            }

            const currentCount = delivered + failed;
            const percent = Math.round((currentCount / total) * 100);
            $('#smsProgressBar').css('width', percent + '%').text(percent + '%');
            $('#smsProgressText').text(`${currentCount} / ${total}`);
            $('#smsDeliverySummary').html(`<span class="text-success">${delivered} Delivered</span> | <span class="text-danger">${failed} Failed</span>`);
        }

        // Reset UI so user can send again without page refresh
        $('#schoolID, #userType, #smsMessage, #selectAllUsers').prop('disabled', false);
        $('#selectAllUsers').prop('checked', false);

        $('#usersTbody tr').each(function() {
            const tr = $(this);
            const phone = String(tr.data('phone') || '').trim();
            const hasPhone = phone && !['null', 'undefined', 'n/a'].includes(phone.toLowerCase());
            const cb = tr.find('.user-checkbox');
            if (cb.length) {
                cb.prop('disabled', !hasPhone);
                cb.prop('checked', false);
            }
        });

        updateSelectedCount();
        $('#btnSendSms').html('<i class="bi bi-chat-dots"></i> Send SMS').prop('disabled', true);

        Swal.fire({
            title: 'Batch Completed',
            text: `SMS sending finished. Delivered: ${delivered}, Failed: ${failed}` + (failed > 0 && firstErrorMessage ? ` (First error: ${firstErrorMessage})` : ''),
            icon: delivered > 0 ? 'success' : 'info'
        });
    });
});
</script>
@endsection
