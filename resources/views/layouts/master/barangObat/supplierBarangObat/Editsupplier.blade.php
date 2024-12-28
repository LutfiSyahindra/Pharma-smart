<div id="supplierFormContainer" style="display: none;">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="supplierUpdateForm">
                    @csrf
                    <div class="form-group">
                        <label for="role">Kode Supplier</label>
                        <input type="text" class="form-control" id="kode_supplier" name="kode_supplier"
                            placeholder="Enter kode supplier" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier"
                            placeholder="Enter supplier" required>
                    </div>
                    <input type="hidden" id="supplierId" name="supplierId">
                    <br>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="closeSupplierForm()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
