@extends("template.partials.mainapp")
{{-- @extends("layouts.setting.permission.modal") --}}

@section("additional_style")
    @include("template.plugins.datatables")
    @include("template.plugins.mdiicon")
    @include("template.plugins.sweetalert2")
@append

@section("modal_content")
    <form id="lokasiForm">
        @csrf
        <table class="table table-bordered" id="dynamicTable">
            <tr>
                <th>Kode Lokasi</th>
                <th>Lokasi</th>
                <th>Action</th>
            </tr>
            <tr>
                <td><input type="text" name="addmore[0][kode_lokasi]" placeholder="Masukkan Kode Lokasi" class="form-control"
                        required /></td>
                <td><input type="text" name="addmore[0][lokasi]" placeholder="Masukkan Lokasi" class="form-control"
                        required /></td>
                <td><button type="button" name="add" id="add" class="btn btn-success">Tambah</button></td>
            </tr>
        </table>
        <br>
        <input class="btn btn-primary" type="submit" value="Submit">
    </form>
@append

@section("modal_Excell_content")
    <div class="modal-body">
        <form id="lokasiExcellForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file" class="form-label">Upload File Excel</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <div class="alert alert-info" role="alert">
                    <i class="mdi mdi-information-outline me-2"></i><strong>Notes:</strong>
                    <ol class="mt-2">
                        <li><i class="mdi mdi-download me-1"></i> Sebelum melakukan import data, download template terlebih
                            dahulu.</li>
                        <li><i class="mdi mdi-file-edit-outline me-1"></i> Isi data sesuai template.</li>
                        <li><i class="mdi mdi-upload me-1"></i> Upload template.</li>
                    </ol>
                </div>
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-info"><i class="mdi mdi-cloud-upload"></i> Upload</button>
                <button type="button" id="downloadTemplate" class="btn btn-success"><i class="mdi mdi-cloud-download"></i>
                    Download Template</button>
            </div>
        </form>
    </div>
@append

@section("content")

    @include("layouts.master.barangObat.lokasiBarangObat.Editlokasi")

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Tables</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lokasi Table</li>
        </ol>
    </nav>

    @include("layouts.master.barangObat.lokasiBarangObat.modal")
    @include("layouts.master.barangObat.lokasiBarangObat.modalExcell")

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <!-- Tombol yang dikelompokkan dalam satu baris -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal"
                            data-bs-target="#lokasiModal">
                            <i class="mdi mdi-note-plus "></i> Tambah Lokasi
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#lokasiExcellModal">
                            <i class="mdi mdi-file-excel"></i> Import Excel
                        </button>
                    </div>

                    <h6 class="card-title">Data Lokasi</h6>

                    <div class="table-responsive">
                        <table id="tableLokasi" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Lokasi</th>
                                    <th>Lokasi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scriptJS")
    @include("layouts.master.barangObat.lokasiBarangObat.scriptJS")
@append

