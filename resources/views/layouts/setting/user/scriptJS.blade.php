<script>
    $(document).ready(function() {
        let userTable = $('#tableUsers').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("setting.user.table") }}", // Sesuaikan dengan route Anda
                type: "GET"
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'roles',
                    name: 'roles'
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
        $('#signupForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let userId = $('#userId').val(); // Ambil ID user jika ada
            let url = userId ? `/settings/user/${userId}/update` :
                "{{ route("setting.user.store") }}"; // Tentukan URL
            let method = userId ? 'PUT' : 'POST'; // Tentukan metode

            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#exampleModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        $('#signupForm')[0].reset();
                        $('#userId').val(''); // Reset ID
                        userTable.ajax.reload();
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


        $('#exampleModal').on('hidden.bs.modal', function() {
            // Reset form
            document.getElementById('signupForm').reset();
            $('#exampleModalLabel').text('ADD USERS');
            $('#modalSubmitButton').val('Submit');
            $('#password').closest('.mb-3').show();
            $('#confirm_password').closest('.mb-3').show();
        });

        window.roleUser = function(id, userName) {
            // Tampilkan form permission
            const formContainer = document.getElementById('roleFormContainer');
            formContainer.style.display = 'block';
            // Isi roleId dan roleName
            document.getElementById('usersId').value = id;
            document.getElementById('user').value = userName;

            // Ambil data permission dari server
            $.ajax({
                url: `/settings/user/${id}/roles`,
                method: 'GET',
                success: function(response) {
                    const roleSelect = $('.js-example-basic-multiple');
                    roleSelect.empty(); // Hapus opsi sebelumnya

                    // Tambahkan semua role ke select box
                    response.roles.forEach(role => {
                        const selected = response.user_roles.includes(role.id) ?
                            'selected' : '';
                        roleSelect.append(
                            `<option value="${role.id}" ${selected}>${role.name}</option>`
                        );
                    });

                    // Refresh Select2
                    roleSelect.trigger('change');
                },
                error: function(xhr) {
                    console.error('Gagal mengambil data permission', xhr);
                }
            });
        };

        window.closePermissionForm = function closePermissionForm() {
            // Sembunyikan div form
            const formContainer = document.getElementById('roleFormContainer');
            formContainer.style.display = 'none';

            // Reset form jika diperlukan
            document.getElementById('roleForm').reset();
        }

        $('#roleForm').on('submit', function(e) {
            e.preventDefault();

            const usersId = $('#usersId').val();
            const formData = $(this).serialize();

            $.ajax({
                url: `/settings/user/${usersId}/assign-roles`,
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
                        userTable.ajax.reload();
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

        window.editUser = function(id) {
            const modal = $('#exampleModal');
            modal.modal('show');

            $('#signupForm')[0].reset();
            $('#passwordSection').hide(); // Sembunyikan password pada edit
            $('#userId').val(id); // Set ID user
            $('#password').closest('.mb-3').hide();
            $('#confirm_password').closest('.mb-3').hide();

            $.ajax({
                url: `/settings/user/${id}/edit`,
                method: 'GET',
                success: function(response) {
                    $('#exampleModalLabel').text('EDIT USER'); // Ubah judul
                    $('#modalSubmitButton').val('Update'); // Ubah tombol
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                },
                error: function(xhr) {
                    console.error('Gagal mengambil data user', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch user data. Please try again.',
                    });
                    modal.modal('hide');
                }
            });
        };

        window.deleteUser = function(id) {
            // Tampilkan konfirmasi hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'User ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: "{{ route("setting.user.destroy", ":id") }}".replace(':id',
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
                                userTable.ajax.reload(); // Reload DataTables
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
