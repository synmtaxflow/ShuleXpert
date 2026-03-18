@extends('includes.superadmin_nav')

@section('content')
<div class="container-fluid mt-3">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <style>
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

        .kbd-textarea {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            background: #f8f9fa;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            padding: 12px;
            line-height: 1.45;
            font-size: 0.95rem;
        }

        .kbd-textarea:focus {
            background: #ffffff;
            border-color: rgba(148, 0, 0, 0.35);
            box-shadow: 0 0 0 0.2rem rgba(148, 0, 0, 0.15);
        }
    </style>

    <div class="card">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0">System Alerts (Header Notifications)</h5>
            <div>
                <a href="{{ route('superAdminDashboard') }}" class="btn btn-sm btn-outline-primary-custom">Dashboard</a>
                <a href="{{ route('superadmin.schools.index') }}" class="btn btn-sm btn-outline-primary-custom">Schools</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label class="font-weight-bold">Select School</label>
                    <select id="schoolID" class="form-control">
                        <option value="">-- Select School --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->schoolID }}">{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 mb-3">
                    <label class="font-weight-bold">User Type</label>
                    <select id="targetUserType" class="form-control">
                        <option value="">-- Select --</option>
                        @foreach($userTypes as $ut)
                            <option value="{{ $ut }}">{{ $ut }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-5 mb-3" id="targetFilterWrap" style="display:none;">
                    <label class="font-weight-bold" id="targetFilterLabel">Target</label>
                    <select id="targetFilter" class="form-control"></select>
                    <small class="text-muted" id="targetHelp"></small>
                </div>
            </div>

            <form id="alertForm" class="border rounded p-3 mb-4" style="background: rgba(148,0,0,0.02);">
                <input type="hidden" id="alertId" value="">

                <div class="row">
                    <div class="col-lg-3 col-md-4 col-6 mb-3">
                        <label class="font-weight-bold">Alert Type</label>
                        <select id="alertType" class="form-control">
                            <option value="info">Info</option>
                            <option value="warning">Warning</option>
                            <option value="success">Success</option>
                            <option value="danger">Danger</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <label class="font-weight-bold">Font Size</label>
                        <select id="fontSize" class="form-control">
                            <option value="">Default</option>
                            <option value="12px">12px</option>
                            <option value="14px">14px</option>
                            <option value="16px">16px</option>
                            <option value="18px">18px</option>
                            <option value="20px">20px</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <label class="font-weight-bold">Width</label>
                        <select id="alertWidth" class="form-control">
                            <option value="">Auto</option>
                            <option value="100%">100%</option>
                            <option value="80%">80%</option>
                            <option value="60%">60%</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <label class="font-weight-bold">BG Color</label>
                        <input type="color" id="bgColor" class="form-control" value="#fff3cd">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <label class="font-weight-bold">Text Color</label>
                        <input type="color" id="textColor" class="form-control" value="#000000">
                    </div>

                    <div class="col-lg-3 col-md-4 col-12 mb-3 d-flex align-items-end">
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="isMarquee">
                            <label class="custom-control-label" for="isMarquee">Marquee</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="isBold">
                            <label class="custom-control-label" for="isBold">Bold</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="isActive" checked>
                            <label class="custom-control-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Message</label>
                    <textarea id="alertMessage" class="form-control kbd-textarea" rows="3" placeholder="Andika ujumbe wa system alert..."></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-primary-custom mr-2" id="btnReset">Reset</button>
                    <button type="button" class="btn btn-primary-custom" id="btnSave">Save Alert</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="alertsTable">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:70px;">ID</th>
                            <th style="width:120px;">Type</th>
                            <th>Target</th>
                            <th>Message</th>
                            <th style="width:110px;">Active</th>
                            <th style="width:170px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="alertsTbody">
                        <tr><td colspan="6" class="text-center text-muted">Select school and user type to view alerts.</td></tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let alertsTable = null;
    let currentOptions = [];
    let loadedAlerts = [];
    let lastNonCustomType = 'info';

    $('#schoolID').select2({ width: '100%', placeholder: '-- Select School --', allowClear: true });

    function safeText(v) {
        return $('<div>').text(v == null ? '' : String(v)).html();
    }

    function resetForm() {
        $('#alertId').val('');
        $('#alertType').val('info');
        $('#alertWidth').val('');
        $('#bgColor').val('#fff3cd');
        $('#textColor').val('#000000');
        $('#bgColor, #textColor').prop('disabled', false);
        $('#isMarquee').prop('checked', false);
        $('#isBold').prop('checked', false);
        $('#isActive').prop('checked', true);
        $('#fontSize').val('');
        $('#alertMessage').val('');
        $('#btnSave').text('Save Alert');
        lastNonCustomType = 'info';
    }

    function getTargetSelectionPayload() {
        const userType = $('#targetUserType').val();
        if (userType === 'Admin' || userType === 'parent') {
            return { applies_to_all: 1, target_role_id: null, target_profession_id: null };
        }
        const targetVal = $('#targetFilter').val();
        const appliesToAll = (targetVal === '__all__');

        let target_role_id = null;
        let target_profession_id = null;

        if (!appliesToAll) {
            if (userType === 'Teacher') target_role_id = targetVal;
            if (userType === 'Staff') target_profession_id = targetVal;
        }

        return { applies_to_all: appliesToAll ? 1 : 0, target_role_id, target_profession_id };
    }

    async function loadOptions() {
        const schoolID = $('#schoolID').val();
        const userType = $('#targetUserType').val();

        $('#targetFilterWrap').hide();
        $('#targetFilter').empty();
        currentOptions = [];

        if (!schoolID || !userType) return;

        if (userType === 'Admin' || userType === 'parent') {
            $('#targetFilterWrap').hide();
            currentOptions = [];
            return;
        }

        try {
            const res = await $.ajax({
                url: '{{ route("superadmin.system_alerts.options") }}',
                type: 'GET',
                data: { schoolID, user_type: userType }
            });

            if (!res.success) return;

            if (userType === 'Teacher') {
                currentOptions = res.roles || [];
                $('#targetFilterLabel').text('Teacher Role');
                $('#targetHelp').text('Choose a role, or All Teachers');
            } else {
                currentOptions = res.professions || [];
                $('#targetFilterLabel').text('Staff Position');
                $('#targetHelp').text('Choose a position, or All Staff');
            }

            const opts = ['<option value="__all__">All</option>'].concat(
                currentOptions.map(o => `<option value="${safeText(o.id)}">${safeText(o.name)}</option>`)
            );

            $('#targetFilter').html(opts.join(''));
            $('#targetFilterWrap').show();

        } catch (e) {
        }
    }

    function renderTargetText(a) {
        if (a.applies_to_all) {
            return 'All ' + safeText(a.target_user_type);
        }
        if (a.target_user_type === 'Admin' || a.target_user_type === 'parent') {
            return 'All ' + safeText(a.target_user_type);
        }
        if (a.target_user_type === 'Teacher') {
            const role = currentOptions.find(x => String(x.id) === String(a.target_role_id));
            return role ? ('Role: ' + safeText(role.name)) : ('Role ID: ' + safeText(a.target_role_id));
        }
        if (a.target_user_type === 'Staff') {
            const p = currentOptions.find(x => String(x.id) === String(a.target_profession_id));
            return p ? ('Position: ' + safeText(p.name)) : ('Profession ID: ' + safeText(a.target_profession_id));
        }
        return '';
    }

    function initTable() {
        if (alertsTable) {
            alertsTable.destroy();
            alertsTable = null;
        }
        alertsTable = $('#alertsTable').DataTable({
            pageLength: 25,
            ordering: true,
            autoWidth: false,
            columnDefs: [{ orderable: false, targets: [5] }]
        });
    }

    async function loadAlerts() {
        const schoolID = $('#schoolID').val();
        const userType = $('#targetUserType').val();

        resetForm();

        if (alertsTable) {
            alertsTable.clear().destroy();
            alertsTable = null;
        }

        if (!schoolID || !userType) {
            $('#alertsTbody').html('<tr><td colspan="6" class="text-center text-muted">Select school and user type to view alerts.</td></tr>');
            return;
        }

        $('#alertsTbody').html('<tr><td colspan="6" class="text-center text-muted"><div class="spinner-border spinner-border-sm"></div> Loading...</td></tr>');

        try {
            const res = await $.ajax({
                url: '{{ route("superadmin.system_alerts.list") }}',
                type: 'GET',
                data: { schoolID, user_type: userType }
            });

            if (!res.success) {
                $('#alertsTbody').html('<tr><td colspan="6" class="text-center text-danger">Failed to load alerts.</td></tr>');
                return;
            }

            const alerts = res.alerts || [];
            loadedAlerts = alerts;
            if (alerts.length === 0) {
                $('#alertsTbody').html('<tr><td colspan="6" class="text-center text-muted">No alerts found.</td></tr>');
                return;
            }

            const rows = alerts.map(a => {
                const isActive = a.is_active ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';
                const typeBadge = `<span class="badge badge-${safeText(a.alert_type)}">${safeText(a.alert_type)}</span>`;
                const targetText = renderTargetText(a);
                const msg = safeText(a.message);

                return `
                    <tr data-id="${safeText(a.id)}">
                        <td>${safeText(a.id)}</td>
                        <td>${typeBadge}</td>
                        <td>${targetText}</td>
                        <td style="max-width: 520px; white-space: normal;">${msg}</td>
                        <td class="text-center">${isActive}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-primary-custom btn-edit" data-id="${safeText(a.id)}">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${safeText(a.id)}">Delete</button>
                        </td>
                    </tr>
                `;
            }).join('');

            $('#alertsTbody').html(rows);
            initTable();

        } catch (e) {
            const errMsg = (e && e.responseJSON && (e.responseJSON.message || e.responseJSON.error))
                ? (e.responseJSON.message || e.responseJSON.error)
                : 'Network error while loading alerts.';
            $('#alertsTbody').html('<tr><td colspan="6" class="text-center text-danger">' + safeText(errMsg) + '</td></tr>');
        }
    }

    const defaultColors = {
        info: { bg: '#d1ecf1', text: '#0c5460' },
        warning: { bg: '#fff3cd', text: '#856404' },
        success: { bg: '#d4edda', text: '#000000' },
        danger: { bg: '#f8d7da', text: '#721c24' }
    };

    function syncAlertTypeState() {
        const selected = $('#alertType').val();
        const isCustom = selected === 'custom';

        // Always allow edits. If not custom, preload defaults for quick use.
        if (!isCustom) {
            lastNonCustomType = selected;
            const c = defaultColors[selected] || defaultColors.info;
            $('#bgColor').val(c.bg);
            $('#textColor').val(c.text);
        }
    }

    async function refreshAll() {
        await loadOptions();
        await loadAlerts();
    }

    $('#schoolID, #targetUserType').on('change', function() {
        refreshAll();
    });

    $('#alertType').on('change', syncAlertTypeState);

    $('#btnReset').on('click', resetForm);

    $('#btnSave').on('click', async function() {
        const schoolID = $('#schoolID').val();
        const target_user_type = $('#targetUserType').val();
        const message = $('#alertMessage').val();

        if (!schoolID || !target_user_type) {
            Swal.fire({ icon: 'warning', title: 'Select School and User Type', text: 'Please select school and user type first.' });
            return;
        }

        if (!message || message.trim().length === 0) {
            Swal.fire({ icon: 'warning', title: 'Message required', text: 'Please type an alert message.' });
            return;
        }

        const targetPayload = getTargetSelectionPayload();
        const mode = $('#alertType').val();
        const isCustom = mode === 'custom';
        const alertTypeToSave = isCustom ? lastNonCustomType : mode;

        const pickedBg = $('#bgColor').val();
        const pickedText = $('#textColor').val();
        const defaultsForType = defaultColors[alertTypeToSave] || defaultColors.info;

        const shouldPersistCustomColors = isCustom
            || (pickedBg && pickedBg.toLowerCase() !== (defaultsForType.bg || '').toLowerCase())
            || (pickedText && pickedText.toLowerCase() !== (defaultsForType.text || '').toLowerCase());
        const payload = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: $('#alertId').val(),
            schoolID,
            target_user_type,
            alert_type: alertTypeToSave,
            message,
            is_marquee: $('#isMarquee').is(':checked') ? 1 : 0,
            is_bold: $('#isBold').is(':checked') ? 1 : 0,
            font_size: $('#fontSize').val(),
            width: $('#alertWidth').val(),
            bg_color: shouldPersistCustomColors ? pickedBg : '',
            text_color: shouldPersistCustomColors ? pickedText : '',
            is_active: $('#isActive').is(':checked') ? 1 : 0,
            applies_to_all: targetPayload.applies_to_all,
            target_role_id: targetPayload.target_role_id,
            target_profession_id: targetPayload.target_profession_id
        };

        const isEdit = payload.id && String(payload.id).length > 0;
        const url = isEdit ? '{{ route("superadmin.system_alerts.update") }}' : '{{ route("superadmin.system_alerts.store") }}';

        try {
            const res = await $.ajax({ url, type: 'POST', data: payload });
            if (res && res.success) {
                resetForm();
                await loadAlerts();
                Swal.fire({ icon: 'success', title: 'Saved', text: 'Alert saved successfully.' });
                return;
            }
            Swal.fire({ icon: 'error', title: 'Failed', text: (res && (res.message || res.error)) || 'Failed to save alert.' });
        } catch (e) {
            const msg = (e && e.responseJSON && e.responseJSON.errors)
                ? JSON.stringify(e.responseJSON.errors)
                : 'Network error while saving alert.';
            Swal.fire({ icon: 'error', title: 'Failed', text: msg });
        }
    });

    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const a = loadedAlerts.find(x => String(x.id) === String(id));
        if (!a) return;

        $('#alertId').val(a.id);
        const hasCustom = !!(a.bg_color || a.text_color);
        lastNonCustomType = a.alert_type || 'info';
        $('#alertType').val(hasCustom ? 'custom' : lastNonCustomType);
        $('#alertWidth').val(a.width || '');
        if (hasCustom) {
            $('#bgColor').val(a.bg_color || (defaultColors[lastNonCustomType]?.bg || '#fff3cd'));
            $('#textColor').val(a.text_color || (defaultColors[lastNonCustomType]?.text || '#000000'));
        } else {
            const c = defaultColors[lastNonCustomType] || defaultColors.info;
            $('#bgColor').val(c.bg);
            $('#textColor').val(c.text);
        }
        $('#isMarquee').prop('checked', !!a.is_marquee);
        $('#isBold').prop('checked', !!a.is_bold);
        $('#isActive').prop('checked', !!a.is_active);
        $('#fontSize').val(a.font_size || '');
        $('#alertMessage').val(a.message || '');

        if (a.applies_to_all) {
            $('#targetFilter').val('__all__');
        } else {
            if (a.target_user_type === 'Teacher') {
                $('#targetFilter').val(String(a.target_role_id || ''));
            } else {
                $('#targetFilter').val(String(a.target_profession_id || ''));
            }
        }

        $('#btnSave').text('Update Alert');
        $('html, body').animate({ scrollTop: $('#alertForm').offset().top - 80 }, 300);
    });

    $(document).on('click', '.btn-delete', async function() {
        const id = $(this).data('id');
        const a = loadedAlerts.find(x => String(x.id) === String(id));
        if (!a) return;

        const confirm = await Swal.fire({
            title: 'Delete Alert?',
            text: 'This will remove the alert for users.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete'
        });

        if (!confirm.isConfirmed) return;

        try {
            const res = await $.ajax({
                url: '{{ url("super-admin/system-alerts/delete") }}/' + a.id,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') }
            });
            if (res && res.success) {
                await loadAlerts();
                Swal.fire({ icon: 'success', title: 'Deleted', text: 'Alert deleted.' });
                return;
            }
            Swal.fire({ icon: 'error', title: 'Failed', text: 'Failed to delete alert.' });
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Failed', text: 'Network error while deleting.' });
        }
    });

    // init
    resetForm();
    syncAlertTypeState();
});
</script>
@endsection
