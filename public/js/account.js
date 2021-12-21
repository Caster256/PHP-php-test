//modal dom
const account_modal_dom = document.getElementById('accountModal');
const export_modal_dom = document.getElementById('exportModal');
//註冊 modal 物件
const account_modal = new bootstrap.Modal(account_modal_dom);
const export_modal = new bootstrap.Modal(export_modal_dom);

//需更新資料的 id
let data_id = '';
//被選取的資料 id
let selected_id = new Set();

$(function () {
    //入口
    process();
});

/**
 * 程式入口
 */
const process = () => {
    //$("#remark").flexible();

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
    //關閉 modal 時要做的事
    account_modal_dom.addEventListener('hidden.bs.modal', function () {
        //重置表單
        formReset();
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
    $('.data-row').on('click', function () {
        let tr = $(this).closest('tr');
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

    //按下刪除按鈕
    $('.del-btn').on('click', function () {
        if(confirm('確定要刪除 ' + selected_id.size + ' 筆資料?')) {
            $.blockUI({message: $("#wait")});

            deleteData();
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

        $.blockUI({message: $("#wait")});

        $.ajax({
            type: 'post',
            url: 'account/export',
            data: {values: values},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                //關掉等待 gif
                $.unblockUI();

                if(data['status'] === 'success') {
                    //關閉 modal
                    export_modal.toggle();
                    //下載檔案
                    document.location.href = 'account/export/' + data['file_name'];
                } else {
                    alert(data['msg']);
                }
            },
            error: function () {
                //關掉等待 gif
                $.unblockUI();
                alert('server error!');
            }
        });
    });
};

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
    const form = $("#edit-form");

    form[0].reset();
    form.find('label.error').remove();
    form.find('.error').removeClass('error');
    $(":radio[value=0]").removeAttr('checked');
    $(":radio[value=1]").attr('checked', 'checked');
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
        "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]],
        //預設排序
        order: [[1, 'asc']],
        //禁止排序
        columnDefs: [{
            targets: [0],
            orderable: false,
        }]
    });
};

/**
 * 判斷是否啟用刪除按鈕
 */
const enableDelBtn = () => {
    let del_btn = $('.del-btn');

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
 */
const deleteData = () => {
    let values = Array.from(selected_id);

    $.ajax({
        type:'delete',
        url:'account/delData',
        data: {values: values},
        dataType:'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success:function(data) {
            //關掉等待 gif
            $.unblockUI();

            if(data["status"] === "success") {
                //顯示成功的提示
                $.blockUI({
                    message: $("#successUI"),
                    timeout: 1500,
                    theme: true
                });

                //等待 1.5 秒重新整理
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
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
            //關掉等待 gif
            $.unblockUI();
            alert('server error!');
        }
    });
};

/**
 * 驗證處理
 */
const formValidate = () => {
    //驗證表單
    $("#edit-form").validate({
        ignore: ''
        , rules: {
            account: {
                accountRegex: true
            },
            email: {
                email: true
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

            //送出表單資料
            $.ajax({
                url: $('#edit-form').attr('action'),
                type: 'post',
                data: {
                    values: values
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (data) {
                    //關掉等待 gif
                    $.unblockUI();

                    if(data["status"] === "success") {
                        //顯示成功的提示
                        $.blockUI({
                            message: $("#successUI"),
                            timeout: 1500,
                            theme: true
                        });

                        //等待 1.5 秒重新整理
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        $.blockUI({
                            message: $("#failureUI"),
                            timeout: 1500,
                            theme: true
                        });

                        alert(data["msg"]);
                        $('#submit').removeAttr('disabled');
                    }
                },
                error: function () {
                    //關掉等待 gif
                    $.unblockUI();
                    alert('server error!');
                }
            });
        }
    });

    //加上帳號的正規化驗證
    $.validator.addMethod("accountRegex",function(value,element) {
        //設定只能輸入英數字
        let account_regex =  /^((?=.*[0-9])(?=.*[a-z|A-Z]))^.*$/;
        return this.optional(element)||(account_regex.test(value));
    },"請輸入英數字！");
};

//# sourceURL=account.js
