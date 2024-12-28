<script>
    $(document).ready(function() {

        var i = 0;

        // Menambah input baru
        $("#add").click(function() {
            ++i;
            $("#dynamicTable").append('<tr><td><input type="text" name="addmore[' + i +
                '][kode_satuan]" placeholder="Masukkan Kode Satuan" class="form-control" required /></td><td><input type="text" name="addmore[' +
                i +
                '][satuan]" placeholder="Masukkan Satuan" class="form-control" required /></td><td><button type="button" class="btn btn-danger remove-tr">Hapus</button></td></tr>'
            );
        });

        // Menghapus input
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });

        window.satuanFormContainer = function satuanFormContainer() {
            // Sembunyikan div form
            const formContainer = document.getElementById('satuanFormContainer');
            formContainer.style.display = 'none';

            // Reset form jika diperlukan
            document.getElementById('satuanUpdateForm').reset();
        }

        let satuanTable = $('#tableSatuan').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("master.satuanBarang.table") }}", // Sesuaikan dengan route Anda
                type: "GET"
            },
            columns: [{
                    data: 'DT_RowIndex', // Data yang berasal dari addIndexColumn()
                    name: 'DT_RowIndex', // Nama kolom index
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode_satuan',
                    name: 'kode_satuan'
                },
                {
                    data: 'satuan',
                    name: 'satuan'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#satuanForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route("master.satuanBarang.store") }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#satuanModal').modal('hide');

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

                        $('#satuanForm')[0].reset();

                        // Reload DataTables
                        satuanTable.ajax.reload();
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

        window.editSatuan = function(id) {
            // Tampilkan form permission
            const formContainer = document.getElementById('satuanFormContainer');
            formContainer.style.display = 'block';

            // Isi roleId dan roleName
            document.getElementById('satuanId').value = id;
            $.ajax({
                url: "{{ route("master.satuanBarang.edit", ":id") }}".replace(':id',
                    id),
                method: "GET",
                data: {
                    id: id
                },

                success: function(response) {
                    if (response.status === 'success') {
                        // Isi field form dengan data yang didapat dari response
                        document.getElementById('satuanId').value = response.satuanFind.id;
                        document.getElementById('kode_satuan').value = response.satuanFind
                            .kode_satuan;
                        document.getElementById('satuan').value = response.satuanFind
                            .satuan;
                    } else {
                        // Menangani error jika data tidak ditemukan
                        alert('Data tidak ditemukan.');
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        };

        $('#satuanExcellModal').on('hidden.bs.modal', function() {
            // Reset form saat modal ditutup
            $('#satuanExcellForm')[0].reset(); // Reset seluruh form
            $('#file').val(''); // Reset input file
        });

        $('#satuanUpdateForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            // Ambil ID satuan dari input hidden
            let satuanId = $('#satuanId').val();

            // Serialisasi form data
            let formData = $(this).serialize(); // Ini akan mengambil semua input yang ada di form

            // URL untuk mengupdate data
            let url = "{{ route("master.satuanBarang.update", ":id") }}".replace(':id', satuanId);

            // Tampilkan konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Satuan ini akan diupdate!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request UPDATE menggunakan AJAX
                    $.ajax({
                        url: url, // Endpoint update satuan
                        type: 'PUT', // Gunakan POST karena metode PUT dikirim dalam _method
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
                                satuanFormContainer();
                                satuanTable.ajax
                                    .reload(); // Reload DataTables jika ada
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
                                'Terjadi kesalahan saat mengupdate satuan.',
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
                text: 'Satuan ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: "{{ route("master.satuanBarang.delete", ":id") }}".replace(
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
                                satuanTable.ajax.reload(); // Reload DataTables
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

        $('#downloadTemplate').on('click', function() {
            window.location.href = "{{ route("master.satuanBarang.downloadTemplate") }}";
        });

        $('#satuanExcellForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            // Menampilkan SweetAlert dengan progress bar
            Swal.fire({
                title: 'Mengunggah...',
                html: '<strong>0%</strong>', // Progress akan diperbarui
                icon: 'info',
                showCancelButton: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route("master.satuanBarang.uploadExcel") }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            // Update progress bar
                            let percent = (e.loaded / e.total) * 100;
                            Swal.update({
                                html: '<strong>' + Math.round(percent) +
                                    '%</strong>'
                            });
                        }
                    });
                    return xhr;
                },
                success: function(response) {
                    // Menampilkan SweetAlert sukses setelah upload selesai
                    Swal.fire({
                        title: 'Sukses!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Tutup'
                    }).then(() => {
                        $('#satuanExcellModal').modal('hide');
                        document.getElementById('satuanExcellForm').reset();
                        satuanTable.ajax.reload();

                    });
                },
                error: function(xhr) {
                    // Menampilkan SweetAlert error jika ada kesalahan
                    Swal.fire({
                        title: 'Terjadi Kesalahan!',
                        text: xhr.responseJSON.message,
                        icon: 'error',
                        confirmButtonText: 'Tutup'
                    });
                },
            });
        });

    });
</script>
