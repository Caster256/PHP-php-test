class Ajax {
    constructor(headers) {
        this.headers = headers;
    }

    //呼叫 api
    callApi = (path, type, values) => {
        let list;

        $.ajax({
            url: path,
            type: type,
            data: { values: values },
            headers: this.headers,
            async: false,
            dataType: 'json',
            success: function (data) {
                list = data;
            },
            error: function () {
                list = 'error';
            }
        });

        return list;
    };
}
