<!-- Modal -->
<div class="modal fade" id="accountModal"
     tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountModalLabel">Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-form" method="post" action="{{ asset('account/edit') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="account">
                                <span class="required-star">*</span> 帳號
                            </label>
                            <input type="text" class="form-control"
                                   id="account" name="account" required />
                        </div>
                        <div class="col-md-6">
                            <label for="username">
                                <span class="required-star">*</span> 姓名
                            </label>
                            <input type="text" class="form-control"
                                   id="username" name="username" required />
                        </div>
                    </div>
                    <div class="row l_r">
                        <div class="col-md-6 birthday-div">
                            <label for="birthday">
                                <span class="required-star">*</span> 生日
                            </label>
                            <input type="text" class="form-control datepicker"
                                   id="birthday" name="birthday" readonly required />
                        </div>
                        <div class="col-md-6">
                            <label for="email">
                                <span class="required-star">*</span> 信箱
                            </label>
                            <input type="text" class="form-control"
                                   id="email" name="email" required />
                        </div>
                    </div>
                    <div class="row l_r">
                        <div class="col-md-6">
                            <label>
                                <span class="required-star">*</span> 性別
                            </label><br />
                            <label>
                                <input type="radio" class="form-check-input"
                                       value="1" name="gender" checked />男
                            </label>
                            <label>
                                <input type="radio" class="form-check-input"
                                       value="0" name="gender" />女
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="remark">
                                備註
                            </label>
                            <textarea class="form-control" id="remark" name="remark"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn btn-primary" id="submit">
                    <i class="fas fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>
