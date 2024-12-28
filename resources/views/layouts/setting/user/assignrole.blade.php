<div id="roleFormContainer" style="display: none;">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="roleForm">
                    @csrf
                    <div class="form-group">
                        <label for="role">User</label>
                        <input type="text" class="form-control" id="user" name="user" placeholder="Enter Role"
                            required disabled>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="description">Role</label>
                        <select class="js-example-basic-multiple form-select" name="role[]" multiple="multiple"
                            data-width="100%">
                            <!-- Options akan dimasukkan secara dinamis -->
                        </select>
                    </div>
                    <input type="hidden" id="usersId" name="usersId">
                    <br>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="closePermissionForm()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
