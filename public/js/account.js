//modal dom
const account_modal_dom = document.getElementById('accountModal');
const export_modal_dom = document.getElementById('exportModal');
const import_modal_dom = document.getElementById('importModal');
//註冊 modal 物件
const account_modal = new bootstrap.Modal(account_modal_dom);
const export_modal = new bootstrap.Modal(export_modal_dom);
const import_modal = new bootstrap.Modal(import_modal_dom);

//表單驗證
let validate;
//需更新資料的 id
let data_id = '';
//被選取的資料 id
let selected_id = new Set();
//匯入資料的 form
let formData;
//實體化物件
let api = new Ajax();

$(function () {
    //入口
    process();
});

/**
 * 程式入口
 */
const process = () => {
    //觸發事件
    eventBinding();

    //初始化日期格式
    initDate();

    //初始化 DataTables 套件
    dataTablesInit();

    //驗證處理
    formValidate();
};

/**
 * 觸發事件
 */
const eventBinding = () => {
    let account_tab =  $('#account-info-tab');

    //關閉 modal 時要做的事
    account_modal_dom.addEventListener('hidden.bs.modal', function () {
        //重置表單
        formReset();
    });

    $("#birthday").on('change', function () {
        if($(this).val() !== '') {
            $("#birthday-error").hide();
            $(this).removeClass('error');
        } else {
            $("#birthday-error").show().text('請選擇生日!');
            $(this).addClass('error');
        }
    });

    //按下新增按鈕開啟 modal
    $('#add-account-btn').on('click', function () {
        account_modal.toggle();
    });

    //送出表單
    $('#submit').on('click', function () {
        $('#edit-form').submit();
    });

    //點選清單的資料進行編輯
    account_tab.on('click', '.data-row', function () {
        let tr = $(this).closest('tr');

        //DataTables RWD 的樣式會撤換整個 html 的位置，所以要判斷如果抓不到，則往上一個 tr 找
        if(tr.attr('data-id') === undefined) {
            tr = tr.prev('tr');
        }

        data_id = parseInt(tr.attr('data-id'));

        //將資料寫入 modal 上
        $("#account").val(tr.find('.td-account').text());
        $("#username").val(tr.find('.td-username').text());
        $("#birthday").val(tr.find('.td-birthday').attr('data-val'));
        $("#email").val(tr.find('.td-email').text());
        $("#remark").val(tr.find('.td-remark').text());

        //特殊判斷，判斷當前資料的性別
        let gender = tr.find('.td-gender').attr('data-val');
        if(gender === '0') {
            $(":radio[value=1]").removeAttr('checked');
            $(":radio[value=0]").attr('checked', 'checked');
        } else {
            $(":radio[value=0]").removeAttr('checked');
            $(":radio[value=1]").attr('checked', 'checked');
        }

        account_modal.toggle();
    });

    //按下全選
    $("#del-all-account-info").on('click', function () {
        //判斷是否被打勾
        let is_true = $(this).prop('checked');

        //所有的 checkbox 都打勾或取消
        $(":checkbox[name='del-account-info']").each(function() {
            $(this).prop('checked', is_true);

            //加入 selected_id
            if(is_true) {
                selected_id.add($(this).closest('tr').attr('data-id'));
            }
        });

        //清空 selected_id
        if(!is_true) {
            selected_id = new Set();
        }

        //若 selected_id 有值則啟用
        enableDelBtn();
    });

    //按下單筆的刪除的核取方塊
    $(":checkbox[name='del-account-info']").on('click', function () {
        //判斷是否被打勾
        let is_true = $(this).prop('checked');
        //取得 id
        let id = $(this).closest('tr').attr('data-id');

        //被打勾就加上，反之
        if(is_true) {
            selected_id.add(id);
        } else {
            selected_id.delete(id);
        }

        //若 selected_id 有值則啟用
        enableDelBtn();
    });

    //按下批次刪除按鈕
    $('#del-btn').on('click', function () {
        if(confirm('確定要刪除 ' + selected_id.size + ' 筆資料?')) {
            $.blockUI({message: $("#wait")});

            //整理出要批次刪除的資料
            let values = Array.from(selected_id);
            deleteData(values);
        }
    });

    //按下刪除按鈕
    account_tab.on('click', '.del-btn', function() {
        if(confirm('確定要刪除這筆資料?')) {
            $.blockUI({message: $("#wait")});

            //取得要刪除的資料
            let id = $(this).closest('tr').attr('data-id');
            //DataTables RWD 的樣式會撤換整個 html 的位置，所以要判斷如果抓不到，則往上一個 tr 找
            if(id === undefined) { id = $(this).closest('tr').prev('tr').attr('data-id'); }
            let values = [id];
            deleteData(values);
        }
    });

    //按下匯出，開啟 modal
    $("#export-btn").on('click', function() {
        export_modal.toggle();
    });

    //匯出檔案
    $(".export-btn").on('click', function () {
        let values = {};
        //取得被選取的 id
        values["ids"] = Array.from(selected_id);
        //取得匯出檔案的類型
        values["type"] = $(this).attr('data-type');

        //關閉 modal
        export_modal.toggle();
        $.blockUI({message: $("#wait")});

        //呼叫 api
        let path = 'account/export';
        let type = 'post';
        api.callApi(path, type, values, true);
    });

    //按下匯入按鈕
    $("#import-btn").on('click', function () {
        //開啟 modal
        import_modal.toggle();
    });

    //上傳檔案
    $("#fileMultiple").on("change", function() {
        // 可接受的附檔名
        let validExts = $(this).attr('accept').split(',');
        // let validExts = [".xls", ".xlsx", ".csv"];
        let fileExt = $(this).val();
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));

        //判斷檔案格式是否正確
        if (validExts.indexOf(fileExt) < 0) {
            alert("檔案類型錯誤，可接受的副檔名有：" + validExts.toString());
            return false;
        } else {
            //顯示上傳的檔案名稱
            let li = '';

            let import_file_ul = $("#import_file_ul");

            //清空 ul
            import_file_ul.empty();

            if(this.files && this.files[0]) {
                //上傳檔案
                formData = new FormData();

                //formData.append('file', this.files[0]);
                for (let i = 0; i < this.files.length; i++) {
                    formData.append("file" + i, this.files[i]);

                    li += '<li>' + this.files[i].name + '</li>';
                }
                import_file_ul.append(li);

                $('#import_checked').removeAttr("disabled");
            }
        }
    });

    //按下匯入的確認鍵
    $("#import_checked").on("click", function () {
        //關閉 modal
        import_modal.toggle();
        $.blockUI({message: $("#wait")});

        $.ajax({
            url: 'account/import',
            type: "POST",
            data: formData,
            //必須false才會自動加上正確的 Content-Type
            contentType: false,
            //必須false才會避開 jQuery 對 form data 的默認處理
            //XMLHttpRequest 會對 form data 進行正確的處理
            processData: false,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $.unblockUI();

                if(data["status"] === "success") {
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
                } else {
                    $.blockUI({
                        message: $("#failureUI"),
                        timeout: 1500,
                        theme: true
                    });

                    alert(data["msg"]);
                }
            },
            error: function () {
                $.unblockUI();
                alert("import error!");
            }
        });
    });
};

/**
 * 檢查上傳的檔案格式是否正確
 * @param sender
 * @returns {boolean}
 */
const checkfile = (sender) => {
    // 可接受的附檔名
    let validExts = $('#fileMultiple').attr('accept').split(',');
    // let validExts = [".xls", ".xlsx", ".csv"];
    let fileExt = sender.value;
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {
        alert("檔案類型錯誤，可接受的副檔名有：" + validExts.toString());
        sender.value = null;
        return false;
    } else return true;
}

/**
 * 初始化日期
 */
const initDate = () => {
    $('.datepicker').datepicker({
        container: '#edit-form',
        autoclose: true,
        todayHighlight: true,
        format:'yyyy-mm-dd',
        clearBtn: true
    });
}

/**
 * 重置表單
 */
const formReset = () => {
    //清除驗證
    validate.resetForm();

    //還原預設值
    $("#edit-form")[0].reset();
    $(":radio[value=0]").removeAttr('checked');
    $(":radio[value=1]").attr('checked', 'checked');

    //清空需更新資料的 id
    data_id = '';
};

/**
 * 初始化 DataTables 套件
 */
const dataTablesInit = () => {
    $('#account-info-tab').DataTable({
        responsive: true,
        //啟用排序
        ordering: true,
        //設定顯示的數量
        "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "全部"]],
        //預設排序
        order: [[2, 'asc']],
        //禁止排序
        columnDefs: [{
            targets: [0, 1, 8],
            orderable: false,
        }],
        language:{
            //左下角的文字
            sInfo: '顯示第 _START_ 至 _END_ 筆,共 _TOTAL_ 筆',
            sInfoEmpty: '顯示第 0 至 0 筆,共 0 筆',
            //沒資料時中間顯示的文字
            emptyTable: '尚無資料',
            //右上角的搜尋文字
            search: '搜尋:',
            //右下角的換頁
            paginate: {
                'next': '>',
                'previous': '<'
            },
            //切換筆數的文字
            lengthMenu: '一次顯示 _MENU_ 筆資料'
        }
    });
};

/**
 * 判斷是否啟用刪除按鈕
 */
const enableDelBtn = () => {
    let del_btn = $('#del-btn');

    //若 selected_id 有值就開放刪除按鈕
    if(selected_id.size > 0) {
        del_btn.removeAttr('disabled');
    } else {
        del_btn.attr('disabled', 'disabled');
        $("#del-all-account-info").prop('checked', false);
    }
};

/**
 * 刪除資料
 * @param values
 */
const deleteData = (values) => {
    //呼叫 api
    let path = 'account/delData';
    let type = 'delete';
    api.callApi(path, type, values, true);
};

/**
 * 驗證處理
 */
const formValidate = () => {
    //驗證表單
    validate = $("#edit-form").validate({
        ignore: ''
        , rules: {
            account: {
                accountRegex: true
            },
            email: {
                email: true
            }
        }
        , messages: {
            account: {
                required: '請輸入帳號!'
            },
            username: '請輸入姓名!',
            birthday: '請選擇生日!',
            email: {
                required: '請輸入信箱!',
                email: '信箱的格式有誤!'
            }
        }
        , errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
        , submitHandler: function() {
            //關閉 modal
            account_modal.toggle();
            //開啟 gif 等待
            $.blockUI({message: $("#wait")});

            let values = {};
            //取得表單資料
            $('#edit-form :input').not(':submit, :radio').each(function() {
                let $input = $(this);
                let name = $input.attr('name');
                values[name] = $input.val();
            });

            //取得性別
            values["gender"] = $("input[type='radio']:checked").attr('value');

            //判斷是否為更新
            if(data_id !== '') {
                values["data_id"] = data_id;
            }

            //防止使用者多按
            $('#submit').attr('disabled', 'disabled');

            //呼叫 api
            let path = $('#edit-form').attr('action');
            let type = 'post';
            api.callApi(path, type, values, true);
        }
    });

    //加上帳號的正規化驗證
    $.validator.addMethod("accountRegex",function(value,element) {
        //設定只能輸入英數字
        let account_regex =  /^((?=.*[0-9])(?=.*[a-z|A-Z]))^.*$/;
        return this.optional(element)||(account_regex.test(value));
    },"請輸入英文 + 數字！");
};

//# sourceURL=account.js
