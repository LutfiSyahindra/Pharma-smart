<script>
    $(document).ready(function() {
        let permissionsTable = $('#tablePermissions').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("setting.permissions.table") }}", // Sesuaikan dengan route Anda
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
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Handle form submission
        $('#permissionsForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route("setting.permissions.store") }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#permissionModal').modal('hide');

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

                        $('#permissionsForm')[0].reset();

                        // Reload DataTables
                        permissionsTable.ajax.reload();
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

        window.editPermission = function(id, permissionName) {
            // Tampilkan form permission
            const formContainer = document.getElementById('permissionFormContainer');
            formContainer.style.display = 'block';

            // Isi roleId dan roleName
            document.getElementById('permissionId').value = id;
            document.getElementById('permission').value = permissionName;
        };

        window.closePermissionForm = function closePermissionForm() {
            // Sembunyikan div form
            const formContainer = document.getElementById('permissionFormContainer');
            formContainer.style.display = 'none';

            // Reset form jika diperlukan
            document.getElementById('permissionForm').reset();
        }

        $('#permissionForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            // Ambil ID permission dari input hidden
            let permissionId = $('#permissionId').val();
            let url = "{{ route("setting.permissions.update", ":id") }}".replace(':id', permissionId);
            // Data yang akan dikirim
            let formData = { 
                name: $('#permission').val(), // Nilai input permission
                _method: 'PUT', // Metode untuk update
                _token: '{{ csrf_token() }}' // Token CSRF Laravel
            };

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Permission ini akan di Update!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: url, // Endpoint update permission
                        type: 'POST', // Gunakan POST karena metode PUT dikirim dalam _method
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Diupdate!',
                                    response.message,
                                    'success'
                                );
                                closePermissionForm();
                                permissionsTable.ajax.reload(); // Reload DataTables
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
                                'Terjadi kesalahan saat mengupdate permission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        window.deletePermission = function(id) {
            // Tampilkan konfirmasi hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Permission ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: "{{ route("setting.permissions.delete", ":id") }}".replace(
                            ':id',
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
                                permissionsTable.ajax.reload(); // Reload DataTables
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
