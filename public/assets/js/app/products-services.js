var PS = (function (global, $, _) {
    'use strict';

    var app = {
        table: null,

        modal: $('#resource_modal'),

        deleteImage: true,

        datatable: function() {

            app.table = $('.content-table').DataTable({
                pageLength: 25,
                destroy: true,
                searching: true,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json"
                },
                ajax: {
                    url: laroute.route('search.products.services'),
                    type: 'GET',
                    dataType: 'json',
                    dataSrc: '',
                    data: {}
                },
                columns: [
                    {
                        data: 'id'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'points'
                    },
                    {
                        data: 'path_image',
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<img src="storage/' + sData + '" />');
                        }
                    },
                    {
                        data: 'status',
                        width: '3%',
                        render: function(data, type, row) {
                            return sweet.common.iconStatusApp(row.status);
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '0';
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '0';
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '0';
                        },
                    },
                    {
                        data: null,
                        width: '3%',
                        render: function (data, type, full, meta) {
                            var options = {
                                'data-id':data.id,
                                'data-title':data.title,
                                'data-description':data.description,
                                'data-points':data.points,
                                'data-bonusimage':data.path_image,
                                'data-category':data.category
                            };

                            var buttonsActions = sweet.common.buttons.edit(options) + '  ' + sweet.common.buttons.delete(data.id);

                            return buttonsActions;
                        },
                    },
                    {
                        data: 'category',
                        visible: false
                    },
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
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        },
                    }
                ]
            });

            return this;
        },

        delete: function() {
            $('.content-table').on('click', 'button.delete', function(e) {
                e.preventDefault();
                e.stopPropagation();

                sweet.common.crud.delete({
                    type: 'GET',
                    datatype: 'json',
                    route: laroute.route('delete.products.services'),
                    id: $(this).data('id'),
                    table: app.table
                });
            });
            return this;
        },

        edit: function() {
            $('.content-table').on('click', 'button.edit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const token = $('meta[name="csrf-token"]').attr('content');

                const id = $(this).data('id');

                const product_stamps = $.ajax({
                    method: 'POST',
                    url: '/products-services/stamps',
                    contentType: 'application/json',
                    data: JSON.stringify({
                      _token: token,
                      product_id: id,
                      dataType: 'json',
                    }),
                })

                product_stamps.done(function(data) {
                    if (data.success) {  
                        var datas = data.data;

                        if(6 == datas.product.category_id) {
                            $('[data-social-network]').removeClass('sr-only');
                            $('[data-exibition-time]').removeClass('sr-only');         
                        } else {
                         $('[data-social-network]').addClass('sr-only');
                         $('[data-exibition-time]').addClass('sr-only');
                        }

                        $('.bonus-image').html('');
                        $('.modal-title').html('Manutenção Domínio');
                        $('#action').val('update');
                        $('#action-button').text('Atualizar');
                        $('#id').val(datas.product.id);
                        $('#title').val(datas.product.title);
                        $('#description').val(datas.product.description);
                        $('#points').val(datas.product.points);
                        $('#exibition_time').val(datas.product.exibition_time);
                        $('#image').val('');
                        $('#primary-key').show();

                        $('#category').selectpicker('deselectAll');

                        $('#category').selectpicker('val', datas.product.category_id);
                        $('#category').selectpicker('refresh');

                        $('#social_network').selectpicker('val', datas.product.social_network);
                        $('#social_network').selectpicker('refresh');

                        $('#stamps').selectpicker('deselectAll');  
                        
                        var stamps = datas.stamps;
                        if(stamps != '') {

                            var arrayStamps = [];
                            var i;

                            $('.stamp-image').children("div").remove();
                            $('.stamp-image').hide();

                            for (let index = 0; index < stamps.length; index++) { 
                                arrayStamps.push(stamps[index].id);                

                                /**
                                 * Switch case para colocar a stamp no index correto na renderização da imagem.
                                 * Isto funciona corretamente para adicionar e remover a imagem correta de acordo
                                 * com os selos marcados no dropdown.
                                 * 
                                 */

                                switch (stamps[index].id) {
                                    case 3:
                                        i = 0;
                                        break;
                                    case 4: 
                                        i = 1;
                                        break;
                                    case 5:
                                        i = 2;
                                        break;
                                    case 6:
                                        i = 3;
                                        break;
                                    case 7:
                                        i = 4;
                                        break;
                                    case 8:
                                        i = 5;
                                        break;
                                    case 9:
                                        i = 6;
                                        break;
                                    case 14:
                                        i = 7;
                                        break;
                                    case 15:
                                        i = 8;
                                        break;
                                    case 16:
                                        i = 9;
                                        break;
                                    case 17:
                                        i = 10;
                                        break;
                                    case 30:
                                        i = 11;
                                        break;
                                
                                    default:
                                        break;
                                }

                                $('.stamp-image').append(
                                $('<div />').addClass('col-md-3').attr('id', i).append($('<img />').attr('src', $('#sweetmedia').val() + '/storage/' + stamps[index].icon).addClass('img').addClass('img-stamps'))
                                ).show();
                            }

                            $('#stamps').selectpicker('val', arrayStamps);
                            $('#stamps').selectpicker('refresh');
                        } 
                      
                        $('.bonus-upload').hide();
                        $('.bonus-image').append(
                            $('<div />').addClass('col-md-3').append($('<img />').attr('src', 'storage/' + datas.product.path_image).addClass('img'))
                        ).append(
                            $('<div />').addClass('col-md-5').append($('<button />').attr({
                                'type':'button',
                                'class':'btn btn-danger delete-image',
                                'data-path':datas.product.path_image
                            }).text('Excluir'))
                        ).show();
                        app.modal.modal('show');
                    }
                })
                
                product_stamps.fail((error) => {
                  console.log('Erro: ', error)
                })   
            });

            return this;
        },

        create: function () {
            $('#new').on('click', function() {                
                app.modal.modal('show');
                $('.modal-title').html('Cadastro Produtos/Serviços');
                $('#action').val('create');
                $('#action-button').text('Cadastrar');
                $('#id, #title, #description, #points, #image').val('');

                /**
                 * Reset stamps dropdown.
                 */
                $('#stamps').selectpicker('deselectAll');
                $('#stamps').selectpicker('refresh');

                $('#category').selectpicker('val', '');
                $('#category').selectpicker('refresh');

                $('.bonus-image').hide();
                $('.bonus-upload').show();
                $('#primary-key').hide();
            });
            return this;
        },

        deleteImageBonus: function () {

            $(global.document).on('click', 'button.delete-image', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var
                    path = $(this).data('path'),
                    partsPath = path.split('/'),
                    image = partsPath.pop();

                    // console.log(path);
                    // console.log(partsPath);
                    // console.log(image);


                var jqxhr = $.get(('/products-services/images/delete'), {'path':encodeURIComponent(partsPath.join('/').concat('/')), image:image})
                    .done(function(data){

                        var json = $.parseJSON(data);
                        if (json.status == 'success') {
                            $('.bonus-image').hide();
                            $('.bonus-upload').show();
                        }
                    })
                    .fail(function () {

                    })
                    .always(function () {

                    });

                jqxhr.always(function(data) {

                });
            });
            return this;
        },

        actions: {
            fields: function () {
                return ({
                    'category':$('#category').val(),
                    'title':$('#title').val(),
                    'description':$('#description').val(),
                    'points':$('#points').val(),
                    'image':$('#image').val(),
                    'id': $('#id').val(),
                    'stamps' : $('#stamps').val(),
                    'social_network' : $('#social_network').val(),
                    'exibition_time' : $('#exibition_time').val()
                });
            },

            create: function() {
                try {

                    var promise = sweet.common.crud.save({
                        params  : app.actions.fields(),
                        endpoint: laroute.route('save.products.services'),
                    });

                    promise.fail(function(error) {
                        var message = '', hasErros = false;

                        $.each($.parseJSON(error.responseText).errors, function(index, value) {
                            if (hasErros) {
                                message += '<br>';
                            }

                            message += value;
                            hasErros = true;
                        });

                        sweet.common.message('error', message);
                    });

                    promise.done(function(data) {
                        var type    = 'error';
                        var message = '';

                        if (data.status == 'success') {
                            //app.table.ajax.reload().desc;                            
                            //app.modal.modal('hide');

                            type    = 'success';
                            message = 'Dados cadastrado com sucesso';
                        }

                        sweet.common.message(type, message);
                        location.reload(true);
                    });
                } catch (e) {
                    sweet.msgException.msg(e, 'domains.app.actions.create');
                }
            },

            update: function() {
                try {
                    var promise = sweet.common.crud.save({
                        params  : app.actions.fields(),
                        endpoint: laroute.route('update.products.services'),
                    });

                    promise.fail(function(error) {
                        console.log(error);
                    });

                    promise.done(function(data) {
                        var type    = 'error';
                        var message = '';

                        if (data.status == 'success') {
                            //app.table.ajax.reload();                            
                            //app.modal.modal('hide');

                            type    = 'success';
                            message = 'Dados alterado com sucesso';
                        }

                        sweet.common.message(type, message);
                        location.reload(true);
                        
                    });
                } catch (e) {
                    sweet.msgException.msg(e, 'domains.app.actions.create');
                }
            }
        },

        buttonClick: function() {
            $('#action-button').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                app.deleteImage = false;

                if ($('#category').val() == '' || $('#title').val() == '' ||
                    $('#description').val() == '' || $('#points').val() == '' ||
                    $('#stamps').val() == '') {                    
                    sweet.common.message('error', 'Todos os campos são obrigatórios');
                    return false;
                }

                if ($('#action').val() === 'create') {
                    app.actions.create();
                } else {
                    app.actions.update();
                }

                $('.fileinput').fileinput('clear');
            })
        },

        init: function() {
            this
                .datatable()
                .create()
                .edit()
                .delete()
                .deleteImageBonus()
                .buttonClick();

            sweet.images.upload({
                'field':'#image',
                'form':'#form-ps',
                'route':'upload.products.services'
            });

            app.modal.on("hide.bs.modal", function () {
                if (app.deleteImage) {
                    sweet.images.deleteUploadImage({
                        'route': 'delete.products.services',
                        'showMessage': false
                    })
                }  
                //remove DIV stamps
                $('.stamp-image').children("div").remove();
                $('.stamp-image').hide();  
                
                $('[data-social-network]').addClass('sr-only');
                $('[data-exibition-time]').addClass('sr-only');
            }); 
            
            $('#category').on('change', function (event) {
               
               if(6 == $('#category').val()) {
                   $('[data-social-network]').removeClass('sr-only');
                   $('[data-exibition-time]').removeClass('sr-only');

               } else {
                $('[data-social-network]').addClass('sr-only');
                $('[data-exibition-time]').addClass('sr-only');
               }
            });
                        
            $('#stamps').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) { 

                if(isSelected){

                    const token = $('meta[name="csrf-token"]').attr('content');

                    var stamps_id = $('#stamps').val();

                    //este método não funcionou para a última stamp!
                    var last_id = $(stamps_id).get(-1);

                    /**
                     *  Switch case para pegar o id da stamp selecionada
                     * 
                     * De acordo com o index selecionado no dropdown,
                     * é identificado qual stamp_id se refere e é feito o ajax com este.
                     */
                    switch (clickedIndex) {
                        case 0:
                        last_id = $('#diginf1').val();
                            break;
                        case 1:
                        last_id = $('#diginf2').val();
                            break;
                        case 2:
                        last_id = $('#diginf3').val();
                            break;
                        case 3:
                        last_id = $('#diginf4').val();
                            break;
                        case 4:
                        last_id = $('#diginf5').val();
                            break;
                        case 5:
                        last_id = $('#diginf6').val();
                            break;
                        case 6:
                        last_id = $('#diginf7').val();
                            break;
                        case 7:
                        last_id = $('#res1').val();
                            break;
                        case 8:
                        last_id = $('#res2').val();
                            break;
                        case 9:
                        last_id = $('#res3').val();
                            break;
                        case 10:
                        last_id = $('#res4').val();
                            break;
                        case 11:
                        last_id = $('#profile').val();
                            break;
                    
                        default:
                            break;
                    }
    
                    const stamps = $.ajax({
                        method: 'POST',
                        url: '/products-services/stamps/getStampsById',
                        contentType: 'application/json',
                        data: JSON.stringify({
                          _token: token,
                          ids_stamp: last_id,
                          dataType: 'json',
                        }),
                    })

                    stamps.done(function(data) {
                       if (data.success) {  
                           var stamp = data.data;

                            $('.stamp-image').append(
                            $('<div />').addClass('col-md-3').attr('id', clickedIndex).append($('<img />').attr('src', $('#sweetmedia').val() + '/storage/' + stamp.icon).addClass('img').addClass('img-stamps'))
                            ).show();
                      }
                    })

                    stamps.fail((error) => {
                        console.log('Erro: ', error)
                    }) 
                    
                } else {
                    //remove stamp if not selected
                    var stamp = document.getElementById(clickedIndex);   
                    if(stamp) {
                        stamp.remove(stamp);
                    }               
                    
                }

                /*

                TRATAMENTO DOS BOTÕES 'SELECIONAR TODOS / DESMARCAR TODOS

                $(".bs-select-all").on('click', function() {
                    const token = $('meta[name="csrf-token"]').attr('content');
                    console.log("select all");

                    //AJAX PARA RECUPERAR TODAS STAMPS
                    const all_stamps = $.ajax({
                        method: 'GET',
                        url: '/products-services/stamps/allStamps',
                        contentType: 'application/json',
                        data: JSON.stringify({
                          _token : token,
                        }),
                      })      
                    
                    all_stamps.done(function(data) {
                        if (data.success) {  
                            var stamps = data.data;

                            //remove DIV stamps
                            $('.stamp-image').children("div").remove();
                            $('.stamp-image').hide();

                            for (let index = 0; index < stamps.length; index++) {
                                $('.stamp-image').append(
                                $('<div />').addClass('col-md-3').attr('id', index).append($('<img />').attr('src', 'https://uploaddeimagens.com.br/images/002/249/406/full/Digital_Influencer_7.png?1565289906').addClass('img').addClass('img-stamps'))
                                //$('<div />').addClass('col-md-3').attr('id', index).append($('<img />').attr('src', $('#sweetmedia').val() + '/storage/' + stamps[index].icon).addClass('img').addClass('img-stamps'))
                                ).show();                                
                            }                            
                        }
                    })

                    all_stamps.fail((error) => {
                        console.log('Erro: ', error)
                    }) 
                });

                //remover todos
                $(".bs-deselect-all").on('click', function() {                      
                    $('.stamp-image').children("div").remove();
                    $('.stamp-image').hide();
                });*/
            });
        }
    };

    return app;
})(window, jQuery, _);

PS.init();