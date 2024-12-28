<div id="permissionFormContainer" style="display: none;">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="permissionForm">
                    @csrf
                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" class="form-control" id="role" name="role" placeholder="Enter Role"
                            required disabled>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="description">Permission</label>
                        <select class="js-example-basic-multiple form-select" name="permissions[]" multiple="multiple"
                            data-width="100%">
                            <!-- Options akan dimasukkan secara dinamis -->
                        </select>
                    </div>
                    <input type="hidden" id="roleId" name="roleId">
                    <br>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="closePermissionForm()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
