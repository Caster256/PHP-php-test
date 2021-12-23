<!-- Modal -->
<div class="modal fade" id="exportModal"
     tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">匯出資料</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <span style="color: red;">
                            若沒有勾選帳號則匯出全部的帳號資訊, 有勾選則匯出勾選的帳號資訊
                        </span>
                    </div>
                </div>
                <div class="row l_r">
                    <div class="col-md-6">
                        匯出 txt 檔<br />
                        <button type="button" class="btn btn-primary export-btn" data-type="txt">txt</button>
                    </div>
                    <div class="col-md-6">
                        匯出 excel 檔<br />
                        <button type="button" class="btn btn-primary export-btn" data-type="xlsx">xlsx</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
