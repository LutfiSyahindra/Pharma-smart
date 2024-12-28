<div id="lokasiFormContainer" style="display: none;">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="lokasiUpdateForm">
                    @csrf
                    <div class="form-group">
                        <label for="role">Kode Lokasi</label>
                        <input type="text" class="form-control" id="kode_lokasi" name="kode_lokasi"
                            placeholder="Enter kode lokasi" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                            placeholder="Enter lokasi" required>
                    </div>
                    <input type="hidden" id="lokasiId" name="lokasiId">
                    <br>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="closeLokasiForm()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
