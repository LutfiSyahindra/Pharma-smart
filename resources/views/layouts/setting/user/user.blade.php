@extends("template.partials.mainapp")
@extends("layouts.setting.user.modal")

@section("additional_style")
    @include("template.plugins.datatables")
    @include("template.plugins.mdiicon")
    @include("template.plugins.sweetalert2")
@append

@section("modal_content")
    <form id="signupForm">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" class="form-control" name="name" type="text">
            <div class="invalid-feedback" id="error-name"></div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" name="email" type="email">
            <div class="invalid-feedback" id="error-email"></div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control" name="password" type="password">
            <div class="invalid-feedback" id="error-password"></div>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm password</label>
            <input id="confirm_password" class="form-control" name="password_confirmation" type="password">
        </div>
        <input id="userId" class="form-control" name="userId" type="hidden">
        <input class="btn btn-primary" id="modalSubmitButton" type="submit" value="Submit">
    </form>
@append

@section("content")

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Tables</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Table</li>
        </ol>
    </nav>

    @include("layouts.setting.user.assignrole")

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <i class="mdi mdi-account"></i></button>
                    </div>
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="tableUsers" class="table">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Roles</th>
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
    @include("layouts.setting.user.scriptJS")
@append
