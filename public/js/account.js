//modal dom
const modal_dom = document.getElementById('accountModal');
//註冊 modal 物件
const account_modal = new bootstrap.Modal(modal_dom);

let data_id = '';

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

    //驗證處理
    formValidate();
};

/**
 * 觸發事件
 */
const eventBinding = () => {
    //關閉 modal 時要做的事
    modal_dom.addEventListener('hidden.bs.modal', function () {
        //重置表單
        //formReset();
    });

    //按下新增按鈕開啟 modal
    $('#add-account-btn').on('click', function () {
        account_modal.toggle();
    });

    //顯示 modal 時要做的事
    /*modal_dom.addEventListener('show.bs.modal', function () {

    });*/

    //送出表單
    $('#submit').on('click', function () {
        $('#edit-form').submit();
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
    $("#edit-form")[0].reset();
    $("#account-error, #email-error").remove();
}

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
        let account_regex =  /^((?=.*[0-9])(?=.*[a-z|A-Z]))^.*$/;
        return this.optional(element)||(account_regex.test(value));
    },"請輸入英數字！");
};

//# sourceURL=account.js
