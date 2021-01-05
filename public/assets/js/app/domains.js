var Domains = (function (global, $, _) {
    'use strict';

    var app = {

        table: null,
        modal: $('#resource_modal'),

        datatable: function() {
            app.table = $('.domains-list').DataTable({
                    pageLength: 25,
                    destroy: true,
                    searching: true,
                    responsive: true,
                    dom: '<"html5buttons"B>lTfgitp',
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json"
                    },
                    ajax: {
                        url: laroute.route('search.domains'),
                        type: 'GET',
                        dataType: 'json',
                        dataSrc: '',
                        data: {}
                },
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'link' },
                    {
                        data: 'status',
                        width: '3%',
                        render: function(data, type, row) {
                            return sweet.common.iconStatusApp(row.status);
                        }
                    },
                    {
                        data: null,
                        width: '3%',
                        render: function (data, type, full, meta) {

                            var options = {
                                'data-id':data.id,
                                'data-name':data.name,
                                'data-link':data.link
                            };

                            var buttonsActions = sweet.common.buttons.edit(options) + '  ' + sweet.common.buttons.delete(data.id);

                            return buttonsActions;
                        }
                    }
                ],
                columnDefs: [
                    {
                        targets: 3,
                        className:  'text-center'
                    },
                    {
                        targets: 4,
                        className:  'text-center'
                    }
                ],
                buttons: [
                /*{
                    text: '<span class="fa fa-plus-square-o btn-new-row"></span> Novo Registro ',
                    action: function (e, dt, node, config) {
                        app.modal.modal('show');
                        $('.modal-title').html('Cadastro Domínio');
                        $('#action').val('create');
                        $('#id, #name, #cnpj, #nickname').val('');
                        $('#primary-key').hide();
                    }
                },*/
                {
                    extend: 'excel',
                    title: 'empresas',
                    text: '<span class="fa fa-file-excel-o"></span> Excel ',
                },
                {
                    extend: 'pdf',
                    title: 'empresas',
                    text: '<span class="fa fa-file-pdf-o"></span> PDF '
                },
                {
                    extend: 'print',
                    text: '<span class="fa fa-print"></span> Imprimir ',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }]
            });
            return this;
        },

        delete: function() {
            $('.domains-list').on('click', 'button.delete', function(e) {
                e.preventDefault();
                e.stopPropagation();
                sweet.common.crud.delete({
                    type: 'GET',
                    datatype: 'json',
                    route: laroute.route('delete.domains'),
                    id: $(this).data('id'),
                    table: app.table
                });
            });
            return this;
        },

        edit: function() {
            $('.domains-list').on('click', 'button.edit', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $('.modal-title').html('Manutenção Domínio');
                $('#action').val('update');
                $('#action-button').text('Atualizar');
                $('#id').val( $(this).data('id'));
                $('#name').val( $(this).data('name'));
                $('#link').val( $(this).data('link'));
                $('#primary-key').show();
                app.modal.modal('show');
            });
            return this;
        },

        create: function () {
            $('#new').on('click', function() {
                app.modal.modal('show');
                $('.modal-title').html('Cadastro Domínio');
                $('#action').val('create');
                $('#action-button').text('Cadastrar');
                $('#id, #name, #link').val('');
                $('#primary-key').hide();
            });
            return this;
        },

        actions: {

            fields: function () {
                return ({
                    'name':$('#name').val(),
                    'link':$('#link').val(),
                    'id': $('#id').val()
                });
            },

            create: function() {
                try {


                    var promise = sweet.common.crud.save({
                          endpoint: laroute.route('save.domains'),
                          params: app.actions.fields()
                    });

                    promise.fail(function(error) {
                          console.log(error.responseText);

                          var
                              message = '', hasErros = false;

                          $.each($.parseJSON(error.responseText).errors, function(index, value) {

                              if (hasErros) {
                                  message += '<br />';
                              }

                              message += value;
                              hasErros = true;
                          });

                        sweet.common.message('error', message);
                    });

                    promise.done(function(data) {
                        var
                            type = 'error',
                            message = '';

                        if (data.status == 'success') {
                            app.table.ajax.reload().desc;
                            app.modal.modal('hide');
                            type = 'success';
                            message = 'Dados cadastrado com sucesso';
                        }

                        sweet.common.message(type, message);

                    });


                } catch (e) {
                    sweet.msgException.msg(e, 'domains.app.actions.create');
                }

            },

            update: function() {
                try {

                    var promise = sweet.common.crud.save({
                        endpoint: laroute.route('update.domains'),
                        params: app.actions.fields()
                    });

                    promise.fail(function(error) {
                        console.log(error);
                    });

                    promise.done(function(data) {

                        var
                            type = 'error',
                            message = '';

                        if (data.status == 'success') {
                            app.table.ajax.reload();
                            app.modal.modal('hide');
                            type = 'success';
                            message = 'Dados alterado com sucesso';
                        }

                        sweet.common.message(type, message);
                    });

                    //promise.always(function(data) {
                        //console.log(data);
                    //});

                } catch (e) {
                    sweet.msgException.msg(e, 'domains.app.actions.create');
                }
            }
        },

        buttonClick: function() {
            $('#action-button').on('click', function(e){
                e.preventDefault();
                e.stopPropagation();

                if ($('#name').val() == '' || $('#link').val() == '') {

                    sweet.common.message('error', 'Todos os campos são obrigatórios');
                    return false;
                }

                if ($('#action').val() === 'create') {
                    app.actions.create();
                } else {
                    app.actions.update();
                }
            })
        },

        init: function() {
            this
                .datatable()
                .create()
                .edit()
                .delete()
                .buttonClick();
        }
    };

    return app;
})(window, jQuery, _);

Domains.init();

