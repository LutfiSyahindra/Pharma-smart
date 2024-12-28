<script>
    $(document).ready(function() {
        // Multiple Input
        var i = 0;

        // Menambah input baru
        $("#add").click(function() {
            ++i;
            $("#dynamicTable").append('<tr><td><input type="text" name="addmore[' + i +
                '][kode_supplier]" placeholder="Masukkan Kode supplier" class="form-control" required /></td><td><input type="text" name="addmore[' +
                i +
                '][supplier]" placeholder="Masukkan supplier" class="form-control" required /></td><td><button type="button" class="btn btn-danger remove-tr">Hapus</button></td></tr>'
            );
        });

        // Menghapus input
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });

        // Table
        let supplierTable = $('#tableSupplier').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("master.supplierBarang.table") }}", // Sesuaikan dengan route Anda
                type: "GET"
            },
            columns: [{
                    data: 'DT_RowIndex', // Data yang berasal dari addIndexColumn()
                    name: 'DT_RowIndex', // Nama kolom index
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode_supplier',
                    name: 'kode_supplier'
                },
                {
                    data: 'supplier',
                    name: 'supplier'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // add Supplier
        $('#supplierForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route("master.supplierBarang.store") }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#supplierModal').modal('hide');

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

                        $('#supplierForm')[0].reset();

                        // Reload DataTables
                        supplierTable.ajax.reload();
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

        // edit Supplier
        window.editSupplier = function(id) {
            // Tampilkan form permission
            const formContainer = document.getElementById('supplierFormContainer');
            formContainer.style.display = 'block';

            // Isi roleId dan roleName
            document.getElementById('supplierId').value = id;
            $.ajax({
                url: "{{ route("master.supplierBarang.edit", ":id") }}".replace(':id',
                    id),
                method: "GET",
                data: {
                    id: id
                },

                success: function(response) {
                    if (response.status === 'success') {
                        // Isi field form dengan data yang didapat dari response
                        document.getElementById('supplierId').value = response.supplierFind.id;
                        document.getElementById('kode_supplier').value = response.supplierFind
                            .kode_supplier;
                        document.getElementById('supplier').value = response.supplierFind
                            .supplier;
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

        // Close editSupplier
        window.closeSupplierForm = function closeSupplierForm() {
            // Sembunyikan div form
            const formContainer = document.getElementById('supplierFormContainer');
            formContainer.style.display = 'none';

            // Reset form jika diperlukan
            document.getElementById('supplierForm').reset();
        }

        // Update Supplier
        $('#supplierUpdateForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            // Ambil ID satuan dari input hidden
            let supplierId = $('#supplierId').val();

            // Serialisasi form data
            let formData = $(this).serialize(); // Ini akan mengambil semua input yang ada di form

            // URL untuk mengupdate data
            let url = "{{ route("master.supplierBarang.update", ":id") }}".replace(':id', supplierId);

            // Tampilkan konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Supplier ini akan diupdate!',
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
                                closeSupplierForm();
                                supplierTable.ajax
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
                                'Terjadi kesalahan saat mengupdate supplier.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // delete Supplier
        window.deleteSupplier = function(id) {
            // Tampilkan konfirmasi hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Suplier ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: "{{ route("master.supplierBarang.delete", ":id") }}".replace(
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
                                supplierTable.ajax.reload(); // Reload DataTables
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
                                'Terjadi kesalahan saat menghapus supplier.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        // download template excell
        $('#downloadTemplate').on('click', function() {
            window.location.href = "{{ route("master.supplierBarang.downloadTemplate") }}";
        });

        // upload excell
        $('#supplierExcellForm').on('submit', function(e) {
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
                url: "{{ route("master.supplierBarang.uploadTemplate") }}",
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
                        $('#supplierExcellModal').modal('hide');
                        document.getElementById('supplierExcellForm').reset();
                        supplierTable.ajax.reload();
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
