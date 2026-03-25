@extends('includes.superadmin_nav')

@section('content')
<div class="container-fluid mt-3">
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
        .badge-soft {
            background: rgba(148, 0, 0, 0.08);
            color: #2f2f2f;
            border: 1px solid rgba(148, 0, 0, 0.15);
            font-weight: 700;
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
            <h5 class="mb-0">Registered Schools</h5>
            <div>
                <a href="{{ route('superAdminDashboard') }}" class="btn btn-sm btn-outline-primary-custom">Dashboard</a>
                <a href="{{ route('superadmin.schools.register') }}" class="btn btn-sm btn-primary-custom">Register New School</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="schoolsTable" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>School</th>
                            <th>Reg No</th>
                            <th>Region</th>
                            <th>District</th>
                            <th>Environment</th>
                            <th>Status</th>
                            <th>2FA</th>
                            <th>Students</th>
                            <th>Teachers</th>
                            <th>Staff</th>
                            <th>Parents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $i => $school)
                            @php
                                $sid = $school->schoolID;
                                $studentsCount = $studentsBySchool[$sid] ?? 0;
                                $teachersCount = $teachersBySchool[$sid] ?? 0;
                                $staffCount = $staffBySchool[$sid] ?? 0;
                                $parentsCount = $parentsBySchool[$sid] ?? 0;
                                $logoPath = $school->school_logo ? asset($school->school_logo) : null;
                                $env = $school->environment ?: 'Demo';
                                $twofa = (bool)($school->two_factor_enabled ?? false);
                            @endphp
                            <tr
                                data-school-id="{{ $sid }}"
                                data-school-name="{{ $school->school_name }}"
                                data-environment="{{ $env }}"
                                data-status="{{ $school->status }}"
                                data-twofa="{{ $twofa ? 1 : 0 }}"
                            >
                                <td>{{ $i + 1 }}</td>
                                <td class="text-center">
                                    @if($logoPath)
                                        <img src="{{ $logoPath }}" alt="logo" class="school-logo" id="logo_img_{{ $sid }}">
                                    @else
                                        <div class="school-logo d-inline-flex align-items-center justify-content-center" id="logo_img_{{ $sid }}">
                                            <i class="fa fa-building" style="color: rgba(148, 0, 0, 0.45);"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $school->school_name }}</td>
                                <td>{{ $school->registration_number ?? 'N/A' }}</td>
                                <td>{{ $school->region }}</td>
                                <td>{{ $school->district }}</td>
                                <td><span class="badge badge-soft" id="env_badge_{{ $sid }}">{{ $env }}</span></td>
                                <td><span class="badge badge-soft" id="status_badge_{{ $sid }}">{{ $school->status }}</span></td>
                                <td>
                                    <span class="badge badge-soft" id="twofa_badge_{{ $sid }}">{{ $twofa ? 'Enabled' : 'Disabled' }}</span>
                                </td>
                                <td>{{ $studentsCount }}</td>
                                <td>{{ $teachersCount }}</td>
                                <td>{{ $staffCount }}</td>
                                <td>{{ $parentsCount }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary-custom btn-edit-settings" data-school-id="{{ $sid }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-change-logo" data-school-id="{{ $sid }}">Logo</button>
                                    <button type="button" class="btn btn-sm btn-outline-info btn-change-stamp" data-school-id="{{ $sid }}">Stamp</button>
                                    <button type="button" class="btn btn-sm btn-outline-dark btn-change-signature" data-school-id="{{ $sid }}">Sign</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted">No schools found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSchoolSettingsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit School Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editSchoolSettingsForm">
                        <input type="hidden" name="schoolID" id="edit_schoolID">
                        <div class="mb-3">
                            <label class="form-label">School</label>
                            <input type="text" class="form-control" id="edit_school_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Environment</label>
                            <select class="form-control" name="environment" id="edit_environment">
                                <option value="Demo">Demo</option>
                                <option value="Live">Live</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" id="edit_status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="edit_twofa" name="two_factor_enabled">
                            <label class="form-check-label" for="edit_twofa">Enable Two Factor Authentication (2FA)</label>
                        </div>
                    </form>
                    <div class="text-danger mt-2" id="edit_settings_error" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="btnSaveSchoolSettings">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeSchoolLogoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update School Logo</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changeSchoolLogoForm" enctype="multipart/form-data">
                        <input type="hidden" name="schoolID" id="logo_schoolID">
                        <div class="mb-3 text-center">
                            <label class="form-label d-block text-left">Current Logo</label>
                            <div id="current_logo_preview" class="mb-2"></div>
                            <input type="file" class="form-control" name="school_logo" id="logo_file" accept="image/*" required>
                        </div>
                    </form>
                    <div class="text-danger mt-2" id="logo_error" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="btnUploadSchoolLogo">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- School Stamp Modal -->
    <div class="modal fade" id="changeSchoolStampModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update School Stamp</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changeSchoolStampForm" enctype="multipart/form-data">
                        <input type="hidden" name="schoolID" id="stamp_schoolID">
                        <div class="mb-3 text-center">
                            <label class="form-label d-block text-left">Current Stamp</label>
                            <div id="current_stamp_preview" class="mb-2"></div>
                            <input type="file" class="form-control" name="school_stamp" id="stamp_file" accept="image/*" required>
                        </div>
                    </form>
                    <div class="text-danger mt-2" id="stamp_error" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="btnUploadSchoolStamp">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- School Signature Modal -->
    <div class="modal fade" id="changeSchoolSignatureModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update School Signature</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changeSchoolSignatureForm" enctype="multipart/form-data">
                        <input type="hidden" name="schoolID" id="signature_schoolID">
                        <div class="mb-3 text-center">
                            <label class="form-label d-block text-left">Current Signature</label>
                            <div id="current_signature_preview" class="mb-2"></div>
                            <input type="file" class="form-control" name="school_signature" id="signature_file" accept="image/*" required>
                        </div>
                    </form>
                    <div class="text-danger mt-2" id="signature_error" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="btnUploadSchoolSignature">Upload</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        if (typeof $ === 'undefined') return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $(document).ready(function() {
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#schoolsTable')) {
                $('#schoolsTable').DataTable().destroy();
            }
            if ($.fn.DataTable) {
                $('#schoolsTable').DataTable({
                    pageLength: 25,
                    order: [[0, 'desc']],
                    autoWidth: false
                });
            }
        });

        $(document).on('click', '.btn-edit-settings', function() {
            const sid = $(this).data('school-id');
            const $row = $('tr[data-school-id="' + sid + '"]');

            $('#edit_settings_error').hide().text('');
            $('#edit_schoolID').val(sid);
            $('#edit_school_name').val($row.data('school-name'));
            $('#edit_environment').val($row.data('environment') || 'Demo');
            $('#edit_status').val($row.data('status') || 'Active');
            $('#edit_twofa').prop('checked', String($row.data('twofa')) === '1');

            $('#editSchoolSettingsModal').modal('show');
        });

        $('#btnSaveSchoolSettings').on('click', function() {
            const sid = $('#edit_schoolID').val();
            const payload = {
                schoolID: sid,
                environment: $('#edit_environment').val(),
                status: $('#edit_status').val(),
                two_factor_enabled: $('#edit_twofa').is(':checked') ? 1 : 0
            };

            $('#edit_settings_error').hide().text('');

            $.ajax({
                url: '{{ route('superadmin.schools.update_settings') }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: payload,
                success: function(resp) {
                    if (!resp || !resp.success) {
                        $('#edit_settings_error').show().text((resp && resp.message) ? resp.message : 'Failed to update settings');
                        return;
                    }

                    const env = (resp.school && resp.school.environment) ? resp.school.environment : (payload.environment || 'Demo');
                    const status = (resp.school && resp.school.status) ? resp.school.status : payload.status;
                    const twofa = (resp.school && typeof resp.school.two_factor_enabled !== 'undefined') ? resp.school.two_factor_enabled : (payload.two_factor_enabled ? true : false);

                    const $row = $('tr[data-school-id="' + sid + '"]');
                    $row.data('environment', env);
                    $row.data('status', status);
                    $row.data('twofa', twofa ? 1 : 0);

                    $('#env_badge_' + sid).text(env);
                    $('#status_badge_' + sid).text(status);
                    $('#twofa_badge_' + sid).text(twofa ? 'Enabled' : 'Disabled');

                    $('#editSchoolSettingsModal').modal('hide');
                },
                error: function(xhr) {
                    let msg = 'Failed to update settings';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(v => (Array.isArray(v) ? v[0] : v)).join(' | ');
                    } else if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $('#edit_settings_error').show().text(msg);
                }
            });
        });

        $(document).on('click', '.btn-change-logo', function() {
            const sid = $(this).data('school-id');
            const $img = $('#logo_img_' + sid);
            $('#logo_error').hide().text('');
            $('#logo_schoolID').val(sid);
            $('#logo_file').val('');
            
            if ($img.is('img')) {
                $('#current_logo_preview').html('<img src="' + $img.attr('src') + '" class="school-logo" style="width:100px;height:100px;">');
            } else {
                $('#current_logo_preview').html('<div class="school-logo d-inline-flex align-items-center justify-content-center" style="width:100px;height:100px;"><i class="fa fa-building fa-3x" style="color: rgba(148, 0, 0, 0.45);"></i></div>');
            }
            
            $('#changeSchoolLogoModal').modal('show');
        });

        $('#btnUploadSchoolLogo').on('click', function() {
            const sid = $('#logo_schoolID').val();
            const fileInput = document.getElementById('logo_file');
            if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                $('#logo_error').show().text('Please choose a logo file');
                return;
            }

            if (fileInput.files[0].size > 5 * 1024 * 1024) {
                $('#logo_error').show().text('File is too large! Maximum allowed size is 5 MB.');
                return;
            }

            const formData = new FormData();
            formData.append('schoolID', sid);
            formData.append('school_logo', fileInput.files[0]);

            $('#logo_error').hide().text('');
            $('#btnUploadSchoolLogo').prop('disabled', true).text('Uploading...');

            $.ajax({
                url: '{{ route('superadmin.schools.update_logo') }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    $('#btnUploadSchoolLogo').prop('disabled', false).text('Upload');
                    if (!resp || !resp.success) {
                        $('#logo_error').show().text((resp && resp.message) ? resp.message : 'Failed to update logo');
                        return;
                    }

                    const url = resp.logo_url;
                    const $cell = $('#logo_img_' + sid);
                    if ($cell && $cell.length) {
                        if ($cell.is('img')) {
                            $cell.attr('src', url);
                        } else {
                            $cell.replaceWith('<img src="' + url + '" alt="logo" class="school-logo" id="logo_img_' + sid + '">');
                        }
                    }

                    $('#changeSchoolLogoModal').modal('hide');
                    Swal.fire('Success', 'School logo updated successfully', 'success');
                },
                error: function(xhr) {
                    $('#btnUploadSchoolLogo').prop('disabled', false).text('Upload');
                    let msg = 'Failed to update logo';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(v => (Array.isArray(v) ? v[0] : v)).join(' | ');
                    } else if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $('#logo_error').show().text(msg);
                }
            });
        });

        // Stamp Upload
        $(document).on('click', '.btn-change-stamp', function() {
            const sid = $(this).data('school-id');
            $('#stamp_error').hide().text('');
            $('#stamp_schoolID').val(sid);
            $('#stamp_file').val('');
            $('#current_stamp_preview').html('<div class="text-muted">Loading preview...</div>');
            
            // We could fetch current stamp but for now just show a placeholder or let them upload
            $('#current_stamp_preview').html('<div class="text-muted small">Select a new image to replace current stamp</div>');
            
            $('#changeSchoolStampModal').modal('show');
        });

        $('#btnUploadSchoolStamp').on('click', function() {
            const sid = $('#stamp_schoolID').val();
            const fileInput = document.getElementById('stamp_file');
            if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                $('#stamp_error').show().text('Please choose a stamp image');
                return;
            }

            if (fileInput.files[0].size > 5 * 1024 * 1024) {
                $('#stamp_error').show().text('File is too large! Maximum allowed size is 5 MB.');
                return;
            }

            const formData = new FormData();
            formData.append('schoolID', sid);
            formData.append('school_stamp', fileInput.files[0]);

            $('#stamp_error').hide().text('');
            $('#btnUploadSchoolStamp').prop('disabled', true).text('Uploading...');

            $.ajax({
                url: '{{ route('superadmin.schools.update_stamp') }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    $('#btnUploadSchoolStamp').prop('disabled', false).text('Upload');
                    if (!resp || !resp.success) {
                        $('#stamp_error').show().text((resp && resp.message) ? resp.message : 'Failed to update stamp');
                        return;
                    }
                    $('#changeSchoolStampModal').modal('hide');
                    Swal.fire('Success', 'School stamp updated successfully', 'success');
                },
                error: function(xhr) {
                    $('#btnUploadSchoolStamp').prop('disabled', false).text('Upload');
                    let msg = 'Failed to update stamp';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(v => (Array.isArray(v) ? v[0] : v)).join(' | ');
                    }
                    $('#stamp_error').show().text(msg);
                }
            });
        });

        // Signature Upload
        $(document).on('click', '.btn-change-signature', function() {
            const sid = $(this).data('school-id');
            $('#signature_error').hide().text('');
            $('#signature_schoolID').val(sid);
            $('#signature_file').val('');
            $('#current_signature_preview').html('<div class="text-muted small">Select a new image to replace current signature</div>');
            $('#changeSchoolSignatureModal').modal('show');
        });

        $('#btnUploadSchoolSignature').on('click', function() {
            const sid = $('#signature_schoolID').val();
            const fileInput = document.getElementById('signature_file');
            if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                $('#signature_error').show().text('Please choose a signature image');
                return;
            }

            if (fileInput.files[0].size > 5 * 1024 * 1024) {
                $('#signature_error').show().text('File is too large! Maximum allowed size is 5 MB.');
                return;
            }

            const formData = new FormData();
            formData.append('schoolID', sid);
            formData.append('school_signature', fileInput.files[0]);

            $('#signature_error').hide().text('');
            $('#btnUploadSchoolSignature').prop('disabled', true).text('Uploading...');

            $.ajax({
                url: '{{ route('superadmin.schools.update_signature') }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    $('#btnUploadSchoolSignature').prop('disabled', false).text('Upload');
                    if (!resp || !resp.success) {
                        $('#signature_error').show().text((resp && resp.message) ? resp.message : 'Failed to update signature');
                        return;
                    }
                    $('#changeSchoolSignatureModal').modal('hide');
                    Swal.fire('Success', 'School signature updated successfully', 'success');
                },
                error: function(xhr) {
                    $('#btnUploadSchoolSignature').prop('disabled', false).text('Upload');
                    let msg = 'Failed to update signature';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(v => (Array.isArray(v) ? v[0] : v)).join(' | ');
                    }
                    $('#signature_error').show().text(msg);
                }
            });
        });
    })();
</script>
@endsection
