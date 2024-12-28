<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>

    <body>
        <div class="modal fade" id="lokasiExcellModal" tabindex="-1" aria-labelledby="lokasiExcellModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lokasiExcellModalLabel">IMPORT EXCEL LOKASI</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        @yield("modal_Excell_content")
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="CloseExcell"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>

<!-- Modal -->
