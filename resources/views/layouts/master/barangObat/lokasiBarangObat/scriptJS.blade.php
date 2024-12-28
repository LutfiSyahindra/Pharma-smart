<script>
    $(document).ready(function() {

        var i = 0;

        // Menambah input baru
        $("#add").click(function() {
            ++i;
            $("#dynamicTable").append('<tr><td><input type="text" name="addmore[' + i +
                '][kode_lokasi]" placeholder="Masukkan Kode lokasi" class="form-control" required /></td><td><input type="text" name="addmore[' +
                i +
                '][lokasi]" placeholder="Masukkan lokasi" class="form-control" required /></td><td><button type="button" class="btn btn-danger remove-tr">Hapus</button></td></tr>'
            );
        });

        // Menghapus input
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });

        window.lokasiFormContainer = function lokasiFormContainer() {
            // Sembunyikan div form
            const formContainer = document.getElementById('lokasiFormContainer');
            formContainer.style.display = 'none';

            // Reset form jika diperlukan
            document.getElementById('lokasiUpdateForm').reset();
        }

        let lokasiTable = $('#tableLokasi').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("master.lokasiBarang.table") }}", // Sesuaikan dengan route Anda
                type: "GET"
            },
            columns: [{
                    data: 'DT_RowIndex', // Data yang berasal dari addIndexColumn()
                    name: 'DT_RowIndex', // Nama kolom index
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode_lokasi',
                    name: 'kode_lokasi'
                },
                {
                    data: 'lokasi',
                    name: 'lokasi'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#lokasiForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route("master.lokasiBarang.store") }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#lokasiModal').modal('hide');

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

                        $('#lokasiForm')[0].reset();

                        // Reload DataTables
                        lokasiTable.ajax.reload();
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

        window.editLokasi = function(id) {
            // Tampilkan form permission
            const formContainer = document.getElementById('lokasiFormContainer');
            formContainer.style.display = 'block';

            // Isi roleId dan roleName
            document.getElementById('lokasiId').value = id;
            $.ajax({
                url: "{{ route("master.lokasiBarang.edit", ":id") }}".replace(':id',
                    id),
                method: "GET",
                data: {
                    id: id
                },

                success: function(response) {
                    if (response.status === 'success') {
                        // Isi field form dengan data yang didapat dari response
                        document.getElementById('lokasiId').value = response.lokasiFind.id;
                        document.getElementById('kode_lokasi').value = response.lokasiFind
                            .kode_lokasi;
                        document.getElementById('lokasi').value = response.lokasiFind
                            .lokasi;
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

        $('#lokasiExcellModal').on('hidden.bs.modal', function() {
            // Reset form saat modal ditutup
            $('#lokasiExcellForm')[0].reset(); // Reset seluruh form
            $('#file').val(''); // Reset input file
        });

        $('#lokasiUpdateForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            // Ambil ID satuan dari input hidden
            let lokasiId = $('#lokasiId').val();

            // Serialisasi form data
            let formData = $(this).serialize(); // Ini akan mengambil semua input yang ada di form

            // URL untuk mengupdate data
            let url = "{{ route("master.lokasiBarang.update", ":id") }}".replace(':id', lokasiId);

            // Tampilkan konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Lokasi ini akan diupdate!',
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
                                lokasiFormContainer();
                                lokasiTable.ajax
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

        window.deleteLokasi = function(id) {
            // Tampilkan konfirmasi hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Lokasi ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: "{{ route("master.lokasiBarang.delete", ":id") }}".replace(
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
                                lokasiTable.ajax.reload(); // Reload DataTables
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
                                'Terjadi kesalahan saat menghapus lokasi.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        $('#downloadTemplate').on('click', function() {
            window.location.href = "{{ route("master.lokasiBarang.downloadTemplate") }}";
        });

        $('#lokasiExcellForm').on('submit', function(e) {
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
                url: "{{ route("master.lokasiBarang.uploadTemplate") }}",
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
                        $('#lokasiExcellModal').modal('hide');
                        document.getElementById('lokasiExcellForm').reset();
                        lokasiTable.ajax.reload();

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

        window.closeLokasiForm = function closeLokasiForm() {
            // Sembunyikan div form
            const formContainer = document.getElementById('lokasiFormContainer');
            formContainer.style.display = 'none';

            // Reset form jika diperlukan
            document.getElementById('lokasiForm').reset();
        }

    });
</script>
