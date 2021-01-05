/**
 * @returns {sweet}
 */
var sweet = sweet || {};

/*Exception
 *
 *var error = new Error('erro);
 *error.name = "nome do erro";
 *throw error;
 *
 *Error Name      Description
 *EvalError       An error in the eval() function has occurred.
 *RangeError      Out of range number value has occurred.
 *ReferenceError  An illegal reference has occurred.
 *SyntaxError     A syntax error within code inside the eval() function has occurred.
 *                All other syntax errors are not caught by try/catch/finally, and will
 *                trigger the default browser error message associated with the error.
 *                To catch actual syntax errors, you may use the onerror event.
 *TypeError       An error in the expected variable type has occurred.
 *URIError        An error when encoding or decoding the URI has occurred
 *                (ie: when calling encodeURI()).
 **/
sweet.msgException = {
    msg: function (error, call) {
        if (console && console.log) {
            console.log(
                'ERRO TIPO JS (%s), \n' +
                'MSG ERRO: (%s),    \n' +
                'LINHA: (%d),       \n' +
                'ARQUIVO: (%o)      \n' +
                'STACK: (%S)        \n' +
                'CALL: (%S)          ',
                error.name,
                error.message,
                error.lineNumber,
                error.fileName,
                error.stack,
                call);
        }
    }
};


sweet.common = {

    allowEdit: true,
    clickNewButton: false,
    divWait: $('#modal-loading'),
    token: null,
    timeOut: 3000,
    url: '',
    returnData: null,
    parseMail: /^[a-z0-9.]+@[a-z0-9]+\.[a-z]+(\.[a-z]+)?$/i,
    parseDate: /(0[0-9]|[12][0-9]|3[01])[-\.\/](0[0-9]|1[012])[-\.\/][0-9]{4}/,

    formToJSON: function (fields) {
        return JSON.stringify(fields);
    },

    setToken: function (token) {
        this.token = token;
    },

    setTimeOut: function(value) {
        this.timeOut = value;
    },

    header: function () {
        return ({
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        });

      // alert($('meta[name="csrf-token"]').val());
    },

    keyValidate: function (data, key) {
        return (data[key] !== undefined ? data[key] : '');
    },

    keyValidateDate: function (data, key) {
        return (data[key] !== undefined && (data[key].indexOf('0001-01-01 00:00:00') < 0 && data[key].indexOf('0001-01-01') < 0 ) ? moment(data[key], 'YYYY-MM-DD').format("DD/MM/YYYY") : '');
    },

    onlyNumber: function (value) {
        return value.replace(/([^\d*])/g, '');
    },

    formatterMoney: function (value) {
        var result = 0;

        result = value.replace('.', ''),
            result = result.replace(',', '.');

        return result;
    },

    formatterDate: function (data) {
        if (data.indexOf('<input') !== -1) {
            return data;
        }
        var date = moment(data),
            month = date.get('month') + 1,
            formatted = (date.get('date') > 9 ? date.get('date') : "0" + date.get('date') ) + "/" + (month > 9 ? month : "0" + month) + "/" + date.get('year');

        return data == '0001-01-01' || formatted == '01/1/2001' ? '' : formatted;
    },

    download: function (url) {
        window.location.href = url;
    },

    validateFullName: function (value) {
        var parse = /[A-z][ ][A-z]/;
        return parse.test(value);
    },

    message: function(type, message)
    {
        toastr[type](message);
    },

    iconStatusApp: function(data) {
        if (data == 1) {
            return '<span class="fa fa-circle" style="color: #7CFC00;" title="Sim"></span>';
        }
        return '<span class="fa fa-circle" style="color: #B22222;" title="Não"></span>';
    },

    querystringToHash: function(queryString) {
        var j, q;
        q = queryString.replace(/\?/, "").split("&");
        j = {};
        $.each(q, function(i, arr) {
            arr = arr.split('=');
            return j[arr[0]] = arr[1];
        });
        return j;
    },

    hashToQueryString: function(hash) {
        return $.param(hash);
    },

    buttons: {
        delete: function (id, title, icon) {
            var
                title =  (!sweet.validate.isNull(title) ? title : 'Excluir'),
                icon =  (!sweet.validate.isNull(icon) ? icon : 'fa-trash');
            return '<button class="btn btn-xs btn-danger delete" data-id="' + id + '"><i class="fa ' + icon + '" aria-hidden="true" title="' + title + '"></i></button>';
        },
        edit: function (params) {

            var options = [];

            if (sweet.validate.isObject(params)) {
                $.each(params, function (key, value) {
                    options += key + '="' + value + '" ';
                });
            }

            return ('<button class="btn btn-xs btn-primary edit" ' + options + '><i class="fas fa-pen" aria-hidden="true" title="Editar"></i></button>');
        },
        save: function () {
            return '<button class="btn btn-xs save" style="background-color: #449D44; border-color: #449D44; color: #fff;"><i class="fa fa-check" aria-hidden="true" title="Salvar"></i></button>';
        },
        update: function (id) {
            return '<button data-id="' + id + '" class="btn btn-xs update" style="background-color: #449D44; border-color: #449D44; color: #fff;"><i class="fa fa-check" aria-hidden="true" title="Atualizar"></i></button>';
        },
        cancel: function () {
            return '<button class="btn btn-xs btn-danger cancel"><i class="fa fa-times" aria-hidden="true" title="Cancelar"></i></button>';
        }
    },

    crud: {
        read: function (option) {
            try {

                var
                    requestType = (sweet.validate.isNull(option.type)) ? 'POST' : option.type,
                    dataType = (sweet.validate.isNull(option.dataType)) ? 'json' : option.dataType,
                    timeOut  = (sweet.validate.isNull(option.timeOut)) ? sweet.common.timeOut : option.timeOut;

                return ($.ajax({
                    url: sweet.common.url.concat(option.endpoint),
                    type: requestType,
                    dataType: dataType,
                    cache: false,
                    contentType: "application/json; charset=utf-8",
                    data: sweet.common.formToJSON(option.params),
                    headers: sweet.common.header(),
                    timeout: timeOut,
                }));

            } catch (e) {
                sweet.msgException.msg(e, 'sweet.common.read.save');
            }
        },

        save: function (option) {
            try {
                var requestType = (sweet.validate.isNull(option.type)) ? 'POST' : option.type;
                var dataType    = (sweet.validate.isNull(option.dataType)) ? 'json' : option.dataType;
                var timeOut     = (sweet.validate.isNull(option.timeOut)) ? sweet.common.timeOut : option.timeOut;

                return $.ajax({
                    url        : sweet.common.url.concat( option.endpoint),
                    type       : requestType,
                    data       : sweet.common.formToJSON(option.params),
                    cache      : false,
                    headers    : sweet.common.header(),
                    timeout    : timeOut,
                    dataType   : dataType,
                    contentType: 'application/json; charset=utf-8',
                });
            } catch (e) {
                sweet.msgException.msg(e, 'sweet.common.crud.save');
            }
        },

        delete: function (option) {
            try {                
                Swal.fire({
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não, cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    buttonsStyling: true,
                    title: 'Você deseja realmente excluir?',
                    text: '',
                    preConfirm: function () {
                       return new Promise((resolve, reject) => {
                            $.ajax({
                                url: option.route,
                                type: option.type,
                                dataType: option.datatype,
                                data: {'id': option.id },
                                timeout: sweet.common.timeOut,
                                cache: false,
                                contenType: 'application/x-www.form-urlencoded;charset=UTF-8',
                                headers: sweet.common.header(),
                            }).fail(function(error) {
                                reject('Ocorreu algum erro ou ID não informado corretamente.');
                            }).done(function(data) {
                                option.table.ajax.reload();
                                resolve();
                            }).always(function(data) {
                                console.log(data);
                            });
                        });
                    }
                }).then((result) => {
                     if (result.value) {
                        Swal.fire('', 'Exclusão realizada com sucesso.', 'success');
                    }
                }, function(error) {
                    Swal.fire('', error, 'error');
                });                
            } catch (e) {
                sweet.msgException.msg(e, 'sweet.common.crud.delete');
            }
        }
    }
};

