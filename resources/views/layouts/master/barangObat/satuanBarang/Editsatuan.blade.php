<div id="satuanFormContainer" style="display: none;">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="satuanUpdateForm">
                    @csrf
                    <div class="form-group">
                        <label for="role">Kode Satuan</label>
                        <input type="text" class="form-control" id="kode_satuan" name="kode_satuan"
                            placeholder="Enter kode satuan" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan"
                            placeholder="Enter Satuan" required>
                    </div>
                    <input type="hidden" id="satuanId" name="satuanId">
                    <br>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="closeSatuanForm()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
