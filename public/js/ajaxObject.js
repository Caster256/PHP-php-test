/**
 * 建構式
 * @constructor
 */
const Ajax = function() {
    this.headers = { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') };
};

/**
 * 宣告方法
 * @param path
 * @param type
 * @param values
 * @param closeWaitUI
 */
Ajax.prototype.callApi = function(path, type, values, closeWaitUI = false) {
    $.ajax({
        url: path,
        type: type,
        data: { values: values },
        headers: this.headers,
        dataType: 'json',
        success: function (data) {
            if(closeWaitUI) { $.unblockUI(); }

            if(data['status'] === 'success') {
                //若有回傳檔案名稱表示需要下載
                if(data.hasOwnProperty('file_name')) {
                    //下載檔案
                    document.location.href = 'account/export/' + data['file_name'];
                } else {
                    //顯示成功的提示
                    $.blockUI({
                        message: $("#successUI"),
                        timeout: 1000,
                        theme: true
                    });

                    //等待 1 秒重新整理
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                $('#submit').removeAttr('disabled');
                alert(data['msg']);
            }
        },
        error: function () {
            if(closeWaitUI) { $.unblockUI(); }
            alert('ajax error!');
        }
    });
};

//# sourceURL=ajaxObject.js
