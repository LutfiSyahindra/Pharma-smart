<div id="permissionFormContainer" style="display: none;">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="permissionForm">
                    @csrf
                    <div class="form-group">
                        <label for="role">Permission</label>
                        <input type="text" class="form-control" id="permission" name="permission"
                            placeholder="Enter Permission" required>
                    </div>
                    <input type="hidden" id="permissionId" name="permissionId">
                    <br>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="closePermissionForm()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
