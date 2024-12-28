<script>
    $(document).ready(function() {

        let rolesTable = $('#tableRoles').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("setting.role.table") }}", // Sesuaikan dengan route Anda
                type: "GET"
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'guard_name',
                    name: 'guard_name'
                },
                {
                    data: 'permissions',
                    name: 'permissions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Handle form submission
        $('#roleForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route("setting.role.store") }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#roleModal').modal('hide');

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'success',
                            title: response.message,
                        });

                        $('#roleForm')[0].reset();

                        // Reload DataTables
                        rolesTable.ajax.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            $(`#error-${key}`).text(errors[key][0]);
                            $(`#${key}`).addClass('is-invalid');
                        }
                    }
                }
            });
        });

        window.permission = function(roleId, roleName) {
            // Tampilkan form permission
            const formContainer = document.getElementById('permissionFormContainer');
            formContainer.style.display = 'block';

            // Isi roleId dan roleName
            document.getElementById('roleId').value = roleId;
            document.getElementById('role').value = roleName;

            // Ambil data permission dari server
            $.ajax({
                url: `/settings/role/${roleId}/permissions`,
                method: 'GET',
                success: function(response) {
                    const permissionSelect = $('.js-example-basic-multiple');
                    permissionSelect.empty(); // Hapus opsi sebelumnya

                    // Tambahkan semua permission ke select box
                    response.permissions.forEach(permission => {
                        const selected = response.role_permissions.includes(permission
                            .id) ? 'selected' : '';
                        permissionSelect.append(
                            `<option value="${permission.id}" ${selected}>${permission.name}</option>`
                        );
                    });

                    // Refresh Select2
                    permissionSelect.trigger('change');
                },
                error: function(xhr) {
                    console.error('Gagal mengambil data permission', xhr);
                }
            });
        };


        window.closePermissionForm = function closePermissionForm() {
            // Sembunyikan div form
            const formContainer = document.getElementById('permissionFormContainer');
            formContainer.style.display = 'none';

            // Reset form jika diperlukan
            document.getElementById('permissionForm').reset();
        }

        $('#permissionForm').on('submit', function(e) {
            e.preventDefault();

            const roleId = $('#roleId').val();
            const formData = $(this).serialize();

            $.ajax({
                url: `/settings/role/${roleId}/assign-permissions`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        });

                        // Sembunyikan form dan reset
                        closePermissionForm();
                        rolesTable.ajax.reload();
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to assign permissions. Please try again.',
                    });
                    console.error(xhr);
                }
            });
        });

        window.deleteRole = function(id) {
            // Tampilkan konfirmasi hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Role ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: "{{ route("setting.role.destroy", ":id") }}".replace(':id',
                            id),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Dihapus!',
                                    response.message,
                                    'success'
                                );
                                rolesTable.ajax.reload(); // Reload DataTables
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus role.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    });
</script>
