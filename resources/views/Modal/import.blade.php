<!-- Modal -->
<div class="modal fade" id="importModal"
     tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">匯入資料</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <span style="color: red;">
                            只能匯入 excel 檔 (.xls .xlsx .csv)
                        </span>
                    </div>
                </div>
                <div class="row l_r">
                    <div class="col-md-6">
                        <label for="fileMultiple" class="form-label">上傳檔案</label>
                        <input class="form-control"
                               type="file"
                               id="fileMultiple"
                               multiple
                               accept=".xls,.xlsx,.csv">
                    </div>
                    <div class="col-md-6">
                        <ul id="import_file_ul"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="import_checked" disabled>確定</button>
            </div>
        </div>
    </div>
</div>
