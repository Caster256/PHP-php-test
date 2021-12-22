/**
 * 建構式
 * @constructor
 */
let Ajax = function() {
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
    let list = '';

    $.ajax({
        url: path,
        type: type,
        data: { values: values },
        headers: this.headers,
        async: false,
        dataType: 'json',
        success: function (data) {
            if(closeWaitUI) { $.unblockUI(); }
            list = data;
        },
        error: function () {
            if(closeWaitUI) { $.unblockUI(); }
            alert('ajax error!');
        }
    });

    return list;
};

//# sourceURL=ajaxObject.js
