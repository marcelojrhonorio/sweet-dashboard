(function (window, document, $) {
    'use strict';

    var login = (function()  {

        return {

            authentication: function () {
                try {
                    $('#login').on('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        $(this).prop('disabled', 'disabled').text('Aguarde...');

                        var promise = sweet.common.crud.read({
                            endpoint: laroute.route('login.api'),
                            params: login.fields()
                        });

                        promise.fail(function(error) {
                            console.log(error);
                            sweet.common.message('error', 'E-mail ou senha inválidos');
                            $('#login').prop('disabled', false).text('Login');
                        });

                        promise.done(function(data){

                            if (data.status == 'success' && data.api_key !== '') {
                                window.location.href = laroute.route('index.dashboad');
                            }
                            $('#login').prop('disabled', false).text('Login');

                        });

                        //promise.always(function(data){
                        //    console.log(data);
                       // });
                    });
                } catch (e) {
                    sweet.msgException.msg(e, 'login.authentication');
                }
            },

            fields: function () {
                return ({
                    'email':$('#email').val(),
                    'password':$('#password').val()
                });
            },

            init: function () {

                login.authentication();

            }
        };

    })();

    login.init();

})(window, document, jQuery);