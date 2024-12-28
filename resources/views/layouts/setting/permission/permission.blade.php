@extends("template.partials.mainapp")
@extends("layouts.setting.permission.modal")

@section("additional_style")
    @include("template.plugins.datatables")
    @include("template.plugins.mdiicon")
    @include("template.plugins.sweetalert2")
@append

@section("modal_content")
    <form id="permissionsForm">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" class="form-control" name="name" type="text">
            <div class="invalid-feedback" id="error-name"></div>
        </div>
        <input class="btn btn-primary" type="submit" value="Submit">
    </form>
@append

@section("content")

    @include("layouts.setting.permission.Editpermission")

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Tables</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Table</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#permissionModal">
                            <i class="mdi mdi-account"></i></button>
                    </div>
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="tablePermissions" class="table">
                            <thead>
                                <tr>
                                    <th>Permission</th>
                                    <th>Guard</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scriptJS")
    @include("layouts.setting.permission.scriptJS")
@append
