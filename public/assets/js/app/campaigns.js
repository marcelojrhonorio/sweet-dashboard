var Campaigns = (function (global, $, _) {
    'use strict';

    var app = {

        table: null,
        countConfig: 1,
        dataSearch: {},

        search: function() {
            $('.search').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                app.dataSearch = sweet.common.querystringToHash($('#form-search').serialize());
                app.datatable();
            });
        },

        clear: function() {
            $('.clear').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $('#form-search')[0].reset();

                app.dataSearch = {};
                app.datatable();
            });
        },

        datatable: function() {
            $.fn.dataTable.ext.errMode = 'none';
            app.table = $('.campaigns-list').
            on( 'error.dt', function ( e, settings, techNote, message ) {
                console.warn( 'WARNING:: An error has been reported by DataTables: ', message );
            } )
                .DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                destroy: true,
                searching: true,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json"
                },
                ajax: {
                    url: laroute.route('search.campaigns'),
                },
                columns: [
                    { 
                        data: 'id', 
                        render: function(data, type, full, meta) {
                            data = '<a href="/campaigns/edit/' + data + '">' + data + '</a>';
                            return data;
                        }
                    },
                    { data: 'name' },
                    { data: 'title' },
                    { data: 'question' },
                    {
                        data: 'path_thumbnail',
                        width: '3%',
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<img src="storage/' + sData + '" />');
                        }
                    },
                    { data: 'order'},
                    {
                        data: 'status',
                        width: '3%',
                        render: function(data, type, row) {
                            return sweet.common.iconStatusApp(row.status);
                        }
                    },
                    {
                        data: 'desktop',
                        width: '3%',
                        render: function(data, type, row) {
                            return sweet.common.iconStatusApp(row.desktop);
                        }
                    },
                    {
                        data: 'mobile',
                        width: '3%',
                        render: function(data, type, row) {
                            return sweet.common.iconStatusApp(row.mobile);
                        }
                    },
                    {
                        data: 'actions',
                        width: '3%',
                        render: function(data, type, row) {
                            return sweet.common.iconStatusApp(row.actions);
                        }
                    },
                    {
                        data: 'postback_url',
                        visible: false
                    },
                    {
                        data: 'config_page',
                        visible: false
                    },
                    {
                        data: 'config_email',
                        visible: false
                    },
                    { data: 'visualized' },
                    { data: 'total_answers' },
                    { data: 'id_has_offers' },
                    { data: 'campaign_types_id' },
                    { data: 'companies_id' },
                    {
                        data: null,
                        width: '3%',
                        render: function (data, type, full, meta) {

                            var options = {
                                'data-id':data.id
                            };

                            var buttonsActions = sweet.common.buttons.edit(options) + '  ' + sweet.common.buttons.delete(data.id, 'Desativar', 'fa-times');

                            return buttonsActions;
                        }
                    }
                ],
                order: [5, 'asc'],
                columnDefs: [
                    {
                        targets: 0,
                        width: '3%',
                    },
                    {
                        targets: 4,
                        className: 'text-center'
                    },
                    {
                        targets: 5,
                        className: 'text-center'
                    },
                    {
                        targets: 6,
                        className: 'text-center'
                    },
                    {
                        targets: 7,
                        className: 'text-center'
                    }
                ],
                buttons: [
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
                        customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                        }
                    }
                ]
            });
            return this;
        },

        formCreate: function() {
            $('.btn-new-row').on('click', function() {
                global.location.href = laroute.route('create.campaigns');
            });
        },

        changeStatus: function(id, status) {
            var jqxhr = $.get(laroute.route('edit.status.campaigns', {'active': status, 'id': id}))
                .done(function() {
                    sweet.common.message('success', 'Status da Campanha alterado com sucesso!');
                })
                .fail(function() {
                    sweet.common.message('error', 'Ops, ocorreu algum erro ao alterar o status da Campanha!');
                })
                .always(function() {

                });
            return jqxhr;
        },

        buttonDelete: function() {
            $('.campaigns-list').on('click', 'button.delete', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var id = $(this).data('id');

                var jqxhr = app.changeStatus(id, 0);

                jqxhr.always(function() {
                    app.table.ajax.reload();
                });

            });

        },

        page: {

            filterHandle: function() {

                $('#filter_operation_begin').on('change', function() {


                    switch ($(this).val()) {
                        case '=':
                        case '<':
                        case '<=':
                        case '<>':
                            $('.block-end').hide();
                            break;
                        case '>':
                        case '>=':


                            $('.block-end').show();
                            break;
                    }


                });
            },

            configRemove: function() {
                $(_).on('click', '.remove', function () {
                    $(this).parents('div.additional').remove();
                    return false;
                });

                $(_).on('click', '.remove-table-edit', function () {

                    var tr = $(this).closest('tr');
                    tr.css('background-color', '#FEFEFE');
                    tr.fadeOut(400, function(){
                        tr.remove();
                    });

                    app.countConfig = app.countConfig - 1;

                    if (app.countConfig < 2) {
                        $('.btn-save-all').hide();
                    }
                    return false;
                });
            },

            create: {

                configAdd: function () {

                    $('.add-inputs-config').on('click', function() {
                        var div = $('#group-config-coreg');

                        $('<div />').attr('class', 'additional').append(
                            $('<div />').addClass('col-md-5').append($('<label />').html('Resposta')).append($('<input />').attr({'type':'text', 'id':'campaigns_clickout_answer' + app.countConfig, 'name':'campaigns_clickout_answer['+ app.countConfig + ']'}).addClass('input-sm form-control'))
                        ).append(
                            $('<div />').addClass('col-md-1').css('top', '25px').append($('<input />').attr({'type':'checkbox', 'id':'campaigns_clickout_affirmative' + app.countConfig, 'name':'campaigns_clickout_affirmative['+ app.countConfig + ']', 'value':'1'}).addClass('checkbox icheckbox')).append($('<label />').html('Sim?'))
                        ).append(
                            $('<div />').addClass('col-md-5').append($('<label />').html('Link')).append($('<input />').attr({'type':'text', 'id':'campaigns_clickout_link' + app.countConfig, 'name':'campaigns_clickout_link['+ app.countConfig + ']', 'placeholder':'http://'}).addClass('input-sm form-control'))
                                .append($('<a />').attr({'href':'javacript:void(0);', 'class':'remove'}).addClass('btn btn-danger').css({'position':'absolute', 'float':'right', 'right':'-50px', 'top':'20px'}).append($('<span />').append($('<i />').attr('aria-hidden', 'true').addClass('fa fa-minus-circle'))))
                        ).appendTo(div);

                        sweet.icheck.init('.icheck');
                        app.countConfig = app.countConfig + 1;
                        return false;
                    });
                },

                selectCampaign: function () {

                    //on('ifCreated ifClicked ifChanged ifChecked ifUnchecked ifDisabled ifEnabled ifDestroyed', function(event) {
                    const $typeInput = $('.campaign-types input');

                    $typeInput.on('ifClicked, ifChanged', function (event) {
                        const type = $(this).data('type').toLowerCase();
                        const $inputsContainer = $('[data-catch-inputs-container]');

                        if ('captura' === type) {
                            $inputsContainer.removeClass('sr-only');

                            return;
                        }

                        $inputsContainer.addClass('sr-only');
                    });

                    $typeInput.on('ifClicked, ifChanged', function(e) {
                        var data = $(this).data('type');

                        $('.coreg-simples').hide();

                        if (data.toLowerCase().indexOf('simples') > -1) {
                            $('.coreg-simples').show();
                        }

                        $('.type-text').html(data);

                        $('.type').show();
                    });
                    return this;
                }
            },

            update: {
                buttonEdit: function() {
                    $('.campaigns-list').on('click', 'button.edit', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        var
                            id = $(this).data('id'),
                            route = 'edit.campaigns'.concat('/', id);

                        global.location.href = laroute.route('edit.campaigns', {'id':id});
                    })
                },

                deleteImage: function () {

                    $('.delete-image').on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        var
                            path = $(this).data('path'),
                            partsPath = path.split('/'),
                            image = partsPath.pop();

                        var jqxhr = $.get(laroute.route('delete.image.campaigns', {'path':encodeURIComponent(partsPath.join('/').concat('/')), image:image}))
                            .done(function(data){

                                var json = $.parseJSON(data);
                                if (json.status == 'success') {
                                    $('#image-campaign').hide();
                                    $('.fileUploader').show();
                                }
                            })
                            .fail(function () {

                            })
                            .always(function () {

                            });

                        jqxhr.always(function(data) {

                        });
                    });
                },

                changeStatus: function() {

                    $('#campaign-active').on('click', function() {
                        var isChecked = $(this).is(':checked');
                        $(this).val(new Number(isChecked));

                        /*var jqxhr = $.get(laroute.route('edit.status.campaigns', {'active':new Number(isChecked), id:$('#campaign_id').val()}))
                            .done(function() {
                                sweet.common.message('success', 'Status da Campanha alterado com sucesso!');
                            })
                            .fail(function() {
                                sweet.common.message('error', 'Ops, ocorreu algum erro ao alterar o status da Campanha!');
                            })
                            .always(function() {

                            });*/

                        var jqxhr = Campaigns.changeStatus($('#campaign_id').val(), new Number(isChecked));

                        jqxhr.always(function(data) {
                            var json = $.parseJSON(data);
                            if (!sweet.validate.isNull(json.id)) {
                                $('input, select, button, textarea').not('#campaign-active').prop('disabled', !isChecked);
                            }
                        });
                    });
                },

                handleOnload: function () {
                    if (global.location.pathname.indexOf('edit') > -1 ) {
                        $('.campaign-types input:checked').each(function(){
                            var id = $(this).attr('id');
                            $('#' + id).iCheck('uncheck');
                            $('#' + id).iCheck('check');
                        });

                        $('.campaign-types input').iCheck('disable');

                        if ($('#campaign-active').val() == 0) {
                            $('input, select, button, textarea').not('#campaign-active').prop('disabled', true);
                        }
                    }
                    return this;
                },

                addEditAnswers: function () {
                    $('.new-answers').on('click', function() {

                        $('#table-clickout tbody').append($('<tr />').attr('id', 'row-'+ app.countConfig)
                            .append(
                                $('<td />').html('-')
                            ).append(
                                $('<td />').append($('<input />').attr({'type':'text', 'id':'campaigns_clickout_answer' + app.countConfig, 'name':'campaigns_clickout_answer['+ app.countConfig + ']', 'class':'input-sm form-control'}))
                            ).append(
                                $('<td />').append(
                                    $('<input />').attr({'type':'checkbox', 'id':'campaigns_clickout_affirmative' + app.countConfig, 'name':'campaigns_clickout_affirmative['+ app.countConfig + ']', 'value':'1'}).addClass('checkbox icheckbox')
                                )
                            ).append(
                                $('<td />').append(
                                    $('<input />').attr({'type':'text', 'id':'campaigns_clickout_link' + app.countConfig, 'name':'campaigns_clickout_link['+ app.countConfig + ']', 'placeholder':'http://', 'class':'input-sm form-control'})
                                )
                            ).append(
                                $('<td />').append(
                                    $('<div />').attr({'class':'btn-group btn-group-sm', 'style':'float: none;'}).append(
                                        $('<button />').attr({'type':'button', 'class':'remove-table-edit tabledit-edit-button btn btn-sm btn-danger', 'title':'Remover'}).append($('<span />').append($('<i />').attr({'aria-hidden':'true', 'class':'fa fa-minus-circle'})))
                                    ).append(
                                        $('<button />').attr({'type':'button', 'class':'save-new-answer tabledit-edit-button btn btn-sm btn-success', 'title':'Salvar', 'data-countnumber': app.countConfig}).append($('<span />').append($('<i />').attr({'aria-hidden':'true', 'class':'fa fa-save'})))
                                    )
                                )
                            )
                        );

                        if (app.countConfig > 1) {
                            $('.btn-save-all').show();
                        }

                        sweet.icheck.init('.icheck');
                        app.countConfig = app.countConfig + 1;

                    });


                    return this;
                },

                saveAnswers: {

                    contentTable: function (data) {

                        var clone = $('table tr:last').clone();
                        $(".tabledit-span", clone).text("");
                        $(".tabledit-input", clone).val("");

                        $('#table-clickout tbody').append($('<tr />').attr('id', data.id)
                            .append(
                                $('<td />').append(
                                    $('<span />').attr('class', 'tabledit-span tabledit-identifier').html(data.id)
                                ).append(
                                    $('<input />').attr({'type':'hidden', 'name':'id', 'value':data.id, 'disabled':'', 'class':'tabledit-input tabledit-identifier'})
                                )
                            ).append(
                                $('<td />').addClass('tabledit-view-mode').append(
                                    $('<span />').addClass('tabledit-span').html(data.answer)
                                ).append(
                                    $('<input />').attr({'class':'tabledit-input form-control input-sm', 'type':'text', 'name':'answer', 'value':data.answer, 'style':'display: none;', 'disabled':''})
                                )
                            ).append(
                                $('<td />').addClass('tabledit-view-mode').append(
                                    $('<span />').addClass('tabledit-span').html((data.affirmative == 1) ? 'Sim' : 'Não')
                                ).append(

                                    $('<select />').attr({'class':'tabledit-input form-control input-sm', 'name':'affirmative', 'style':'display: none;', 'disabled':''})
                                        .append(
                                            $('<option />').val('Sim').text('Sim')
                                        ).append(
                                            $('<option />').val('Não').text('Não')
                                        )
                                )
                            ).append(
                                $('<td />').addClass('tabledit-view-mode').append(
                                    $('<span />').attr('class', 'tabledit-span').html(data.link)
                                ).append(
                                    $('<input />').attr({'class':'tabledit-input form-control input-sm', 'type':'text', 'name':'link', 'value':data.link, 'style':'display: none;', 'disabled':''})
                                )
                            ).append(
                                $('<td />').attr('style', 'white-space: nowrap; width: 1%;').append(
                                    $('<div />').attr({'class':'tabledit-toolbar btn-toolbar', 'style':'text-align: left;'}).append(

                                       $('<div />').attr({'class':'btn-group btn-group-sm', 'style':'float: none;'}).append(
                                           $('<button />').attr({'type':'button', 'class':'tabledit-edit-button btn btn-sm btn-default', 'style':'float: none;'}).append(
                                               $('<span />').addClass('glyphicon glyphicon-pencil')
                                           )
                                        ).append(
                                           $('<button />').attr({'type':'button', 'class':'tabledit-delete-button btn btn-sm btn-default', 'style':'float: none;'}).append(
                                               $('<span />').addClass('glyphicon glyphicon-trash')
                                           )
                                        )
                                    ).append(
                                        $('<button />').attr({'type':'button', 'class':'tabledit-save-button btn btn-sm btn-success', 'style':'display: none; float: none;'}).html('Salvar')
                                    ).append(
                                        $('<button />').attr({'type':'button', 'class':'tabledit-confirm-button btn btn-sm btn-danger', 'style':'display: none; float: none;'}).html('Confirma?')
                                    )

                                )
                            )
                        );
                    },

                    one: function () {
                        $(_).on('click', '.save-new-answer', function () {

                            var
                                tr = $(this).closest('tr'),
                                number = $(this).data('countnumber'),
                                answer = $('#campaigns_clickout_answer'+number).val(),
                                affirmative = $('#campaigns_clickout_affirmative'+number).is(':checked') ? 1 : 0,
                                link = $('#campaigns_clickout_link'+number).val(),
                                fields = {};


                            fields = {
                                'answer':answer,
                                'affirmative':affirmative,
                                'link':link,
                                'idCampaign': $('#campaign_id').val()
                            };

                            sweet.common.crud.save({
                                endpoint: laroute.route('save.clickout.campaigns'),
                                params: fields
                            }).fail(function(error) {
                                console.log(error.responseText);

                                //sweet.common.message('error', message);
                            }).done(function(data) {

                               //console.log(data);

                                if (data.status == 'success') {
                                    tr.css('background-color', '#FEFEFE');
                                    tr.remove();
                                    app.page.update.saveAnswers.contentTable(data.result);
                                    $('.btn-save-all').hide();
                                    app.countConfig = 0;
                                }
                                //sweet.common.message(type, message);
                            });

                        });

                        return this;
                    },

                    all: function () {

                        $(_).on('click', '.btn-save-news-answers', function () {

                            var
                                answers = [],
                                affirmatives = [],
                                links = [],
                                fields = {};

                            $('input[name^="campaigns_clickout_answer"]').each(function(index) {
                                answers[index] = $(this).val();
                            });

                            $('input[name^="campaigns_clickout_affirmative"]').each(function(index) {
                                affirmatives[index] = $(this).is(':checked') ? 1 : 0;
                            });

                            $('input[name^="campaigns_clickout_link"]').each(function(index) {
                                links[index] = $(this).val();
                            });

                            fields = {
                                'answer':answers,
                                'affirmative':affirmatives,
                                'link':links,
                                'idCampaign': $('#campaign_id').val()
                            };

                            sweet.common.crud.save({
                                endpoint: laroute.route('save.clickout.campaigns'),
                                params: fields
                            }).fail(function(error) {
                                console.log(error.responseText);

                                //sweet.common.message('error', message);
                            }).done(function(data) {

                               // console.log(data);

                                if (data.status == 'success') {
                                    //tr.css('background-color', '#FEFEFE');
                                    //tr.remove();

                                    $.each(data.result, function(index, value) {

                                        $('#row-' + (index + 1)).closest('tr').remove();
                                        app.page.update.saveAnswers.contentTable(value);
                                        $('.btn-save-all').hide();
                                        app.countConfig = 0;
                                    });

                                    //
                                }
                                //sweet.common.message(type, message);
                            });

                        });

                        return this;
                    }

                },

                handlePostbackField: function() {
                    var data = $('input[type="radio"][name="campaigntypes"]:checked').data('type')

                    $('.coreg-simples').hide();

                    if (data && data.toLowerCase().indexOf('simples') > -1) {
                        $('.coreg-simples').show();
                    }

                    $('.type-text').html(data);

                    $('.type').show();
                }
            }
        },

        init: function() {

            $('.selectpicker').selectpicker();
            this.search();
            this.clear();
            this.datatable();
            this.formCreate();
            this.buttonDelete();
            this.page.create.selectCampaign();
            this.page.create.configAdd();
            this.page.configRemove();
            this.page.update.buttonEdit();
            this.page.update.deleteImage();
            this.page.update.changeStatus();
            this.page.update
                .addEditAnswers()
                .saveAnswers.one().all();

            this.page.filterHandle();


            $(global).on('load', function() {
                Campaigns.page.update.handleOnload().handlePostbackField();
            });
        }

    };

return app;
})(window, jQuery, _);

Campaigns.init();

