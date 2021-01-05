(function($) {
    const ScreenExchangesSm = {

    start: function() {
        this.$table = $('[data-table-exchanges-sm]');
        
        this.$modal = $('[data-social-network-modal]');
        this.$modalAction = $('[data-modal]');
        this.$form = $('[data-form-exchages-sm]');
        this.$datatable = null;

        this.$btnConfirm = $('[data-btn-confirm]');

        this.$btnFilter   = $('[data-refresh-filter]');
        this.$textFilter  = $('[filter-users-text]');
        this.$usersFilter = $('[data-filter-users]');
        
        this.$status = $('#status').val();
        this.$btnSubmit = $('[btn-submit-status]');

        this.dataTable();
        this.bind();
    },

    bind: function() {
        this.$btnSubmit.on('click', $.proxy(this.onSubmitClick, this));
        this.$btnConfirm.on('click', $.proxy(this.onBtnConfirmClick, this));
        this.$modal.on('hide.bs.modal', $.proxy(this.onHideModal, this));
        this.$modalAction.on('hide.bs.modal', $.proxy(this.onHideModalAction, this));
        this.$btnFilter.on('click', $.proxy(this.onBtnFilterClick, this));
        this.$table.on('click', '[data-btn-details]', $.proxy(this.onBtnDetailsClick, this));
    },

    dataTable: function() {
        this.$datatable = this.$table.DataTable({
          processing: true,
          serverSide: true,
          pageLength: 25,
          searching: true,
          responsive: true,
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [],
          language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json',
          },
          ajax: {
            url: '/exchanges/social-network/search',
          },
          dataSrc: function(json) {
            const parsed = json.data.map(function(item) {
              if (item.created_at) {
                item.created_at = (new Date(item.created_at)).toLocaleDateString() + ' ' 
                  + (new Date(item.created_at)).getHours()
                  + ':'
                  + (new Date(item.created_at)).getMinutes();
              }

              if (item.updated_at) {
                item.updated_at = (new Date(item.updated_at)).toLocaleDateString() + ' ' 
                + (new Date(item.updated_at)).getHours()
                + ':'
                + (new Date(item.updated_at)).getMinutes();;
              }

              return item;
            });

            return parsed;
          },
          columns: [
            {
              data: 'id',
            },
            {
              data: 'customers_id',
            },
            {
              data: 'fullname',
            },
            {
              data: 'email',
            },
            {
              data: 'subject',
            },
            {
              data: 'profile_link',
            },
            {
                data: 'profile_picture',
                fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                  var url_store = $('[data-store-url]').val();
                  $(nTd).html(`<img style="width:61%;margin-left:20%;" src="${url_store}/storage/${sData}">`);
              }
            },      
            {
                data: 'created_at',
                render: function(data, type, row, meta) {
                  var res = data.split("-");
                  var aux = res[2].split(" ");
                  data = aux[0] + "/" + res[1] + "/" + res[0];
                  return data;
                }
             },
            {
              data : null,
              // width: '3%',
              name: 'status',
              targets: [3],
              orderData:3,
              render: function(data, type, full, meta) {
                this.$status = data.status;

                switch (data.status) {
                  case 'pending':
                    data.status = `
                      <span class="badge badge-secondary">
                        <i class="fas fa-clock"></i> 
                        <strong>1.</strong> Troca de pontos em análise
                      </span>`;
                    break;
                  case 'disapproved':
                    data.status = `
                      <span class="badge badge-danger">
                        <i class="fas fa-bomb"></i> 
                        <strong>2.</strong> Troca de pontos não autorizada
                      </span>`;
                    break;
                  case 'approved':
                    data.status = `
                      <span class="badge badge-light" style="background-color: #2cc963; color: white;">
                        <i class="fas fa-check"></i> 
                        <strong>3.</strong> Troca de pontos autorizada
                      </span>`;
                    break;                  
                  default:
                    data.status = 'Não informado';
                }
  
                return data.status;
                
              },
            }, 
            {
              data: null,
              width: '3%',
              render: function(data, type, full, meta) {
                const btnEdit = `
                  <button
                    class="btn btn-primary"
                    title="Ver detalhes"
                    type="button"
                    data-btn-details
                    data-id="${data.id}"
                    data-customers_id="${data.customers_id}"
                    data-fullname="${data.fullname}"
                    data-email="${data.email}"
                    data-subject="${data.subject}"
                    data-profile_link="${data.profile_link}"
                    data-profile_picture="${data.profile_picture}"
                    data-created_at="${data.created_at}"
                    data-status="${this.$status}"
                  >
                    <span class="sr-only">Detalhes</span>
                    <i class="fas fa-info-circle" aria-hidden="true"></i>
                  </button>
                `;
  
                const buttons = `${btnEdit}`;
  
                return buttons;
              },            
            },          
          ], 
          initComplete: function () {
            this.api().columns([3]).every(function () {
                var column = this;
                var input = document.createElement("input");
                $(input).appendTo($(column.footer()))
                .on('change', function () {
                    column.search($(this).val(), false, false, true).draw();
                });
            });
        }         
        });
      },

      search: function() {
        $('.search').on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();  
          
          app.dataSearch = sweet.common.querystringToHash($('#form-search').serialize());
          app.dataTable();
        });
      },

      onBtnDetailsClick: function(event){
        event.preventDefault();
        event.stopPropagation();
  
        const $btn = $(event.currentTarget);
        const id = $.trim($btn.data('id'));
        const customers_id = $.trim($btn.data('customers_id'));
        const fullname = $.trim($btn.data('fullname'));
        const email = $.trim($btn.data('email'));
        const subject = $.trim($btn.data('subject'));
        const profile_link = $.trim($btn.data('profile_link'));
        const profile_picture = $.trim($btn.data('profile_picture'));
        const status = $.trim($btn.data('status'));

        $('#id').val(id);
        $('#customers_id').val(customers_id);
        $('#fullname').val(fullname);
        $('#email').val(email);
        $('#subject').val(subject);

        $('#profile_link').val(profile_link);        
        $('[btn-view-profile]').attr('href', profile_link);

        var url = $('[ data-store-url]').val() + /storage/ + profile_picture;
        $('#profile_picture').attr('src', url);
       
        $('#status').val(status);       
        $('#status').selectpicker('refresh');

        this.$modal.modal('show');
      },

      onSubmitClick: function(event) {
        event.preventDefault();       

        const token = $('meta[name="csrf-token"]').attr('content');

        const updating = $.ajax({ 
            method: 'POST',
            url: '/exchanges/social-network/update',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              id: $('#id').val(),
              status: $('#status').val(),
              dataType: 'json',
            }),
        })
    
        updating.done($.proxy(this.onUpdatingSuccess, this));
        updating.fail($.proxy(this.onUpdatingFail, this)); 

      },

      onUpdatingSuccess: function(data) {
        
        this.$modal.modal('hide');        

        if('approved' == data.data.status) 
        {
          if(!data.action) 
          {
            //setando valor de category
            $('[data-input-category]').selectpicker('val', 7);
            $('[data-input-category]').selectpicker('refresh');

            //setando valor de type
            $('[data-input-type]').selectpicker('val', 7);
            $('[data-input-type]').selectpicker('refresh');

            //setando valor de url
            $('[data-wrap-type-url]').removeClass('sr-only');
            $('[data-input-type-url]').val(data.data.profile_link);

            //setando valor de enabled
            $('#enabled').selectpicker('val', 1);
            $('#enabled').selectpicker('refresh');
            
            sweet.common.message('success', 'Por favor, informe os dados da ação!');  
            this.$modalAction.modal('show');
            this.$modalAction.css('overflow-y', 'scroll');

            $('[data-wrap-upload]').addClass("sr-only"); 
            $('[data-input-path]').val(data.data.profile_picture);

            var url_store = $('[ data-store-url]').val();

            $('[data-wrap-preview]')
              .html(`
                <div class="col-md-3">
                  <img class="edit-img-action" src="${url_store}/storage/${data.data.profile_picture}" alt="">
                </div>
              `)
              .removeClass('sr-only'); 
          } else {
            sweet.common.message('success', 'Essa solicitação já possui um produto cadastrado!');  
          }          

        } else {
          sweet.common.message('success', 'Troca de pontos atualizada com sucesso!');  
          this.$datatable.ajax.reload().desc;
        }

       
      },
  
      onUpdatingFail: function(error) {
        console.log(error);
      },

      onBtnConfirmClick: function (event) {
        event.preventDefault();   

        if (
          '' === $('[data-input-category]').val()    || 
          '' === $('[data-input-type]').val()        ||
          '' === $('[data-input-type-url]').val()    ||
          '' === $('[data-input-title]').val()       ||
          '' === $('[data-input-description]').val() ||
          '' === $('[data-input-points]').val()      ||
          '' === $('[data-input-order]').val()       ||
          '' === $('#enabled').val()           
        ) {
          sweet.common.message('error', 'Todos os campos são obrigatórios');
          return;
        }

        const token = $('meta[name="csrf-token"]').attr('content');    

        const params = {
          category               : $('[data-input-category]').val(),
          type                   : $('[data-input-type]').val(),
          typeUrl                : $('[data-input-type-url]').val(),
          title                  : $('[data-input-title]').val(),
          description            : $('[data-input-description]').val(),
          points                 : $('[data-input-points]').val(),
          image                  : $('[data-input-image]').val(),
          order                  : $('[data-input-order]').val(),
          enabled                : $('#enabled').val(),
          filter_ddd             : $('[filter_ddd]').val(),
          filter_gender          : $('[filter_gender]').val(),
          filter_cep             : $('[filter_cep]').val(),
          filter_operation_begin : $('[filter_operation_begin]').val(),
          filter_age_begin       : $('[filter_age_begin]').val(),
          filter_operation_end   : $('[filter_operation_end]').val(),
          filter_age_end         : $('[filter_age_end]').val(),
          path                   : $('[data-input-path]').val(),
          exchange_id            : $('[data-exchange-id]').val(),
        };

        const saving = sweet.common.crud.save({
          params  : params, 
          endpoint: '/actions',
        });
  
        saving.done($.proxy(this.onCreateSuccess, this));
  
        saving.fail($.proxy(this.onCreateFail, this));

      },

      onCreateSuccess: function(data) {
        if (data.success) {
          
          this.$modalAction.modal('hide');
          this.$datatable.ajax.reload().desc;
          sweet.common.message('success', 'Dados cadastrados com sucesso!');
        }
      },
  
      onCreateFail: function(error) {
        console.log(error);
      },

      onHideModal: function() {       
        $('#status').selectpicker('val', '');
        $('#status').selectpicker('refresh');
      },

      onHideModalAction: function() {       

        $('[filter_gender]').selectpicker('val', '');
        $('[filter_gender]').selectpicker('refresh');

        $('[filter_operation_begin]').selectpicker('val', '');
        $('[filter_operation_begin]').selectpicker('refresh');

        $('[filter_operation_end]').selectpicker('val', '');
        $('[filter_operation_end]').selectpicker('refresh');

        $('[filter_age_begin]').val('');
        $('[filter_age_end]').val('');
        $('[filter_ddd]').val('');
        $('[filter_cep]').val('');
        $('[data-filter-users]').val('');

        $('[data-input-title]').val('');
        $('[data-input-description]').val('');
        $('[data-input-points]').val('');
        $('[data-input-order]').val('');
      },

      getFilterValues: function()
      {          
        return {
            'filter_gender' : $('[filter_gender]').val(),
            'filter_operation_begin' : $('[filter_operation_begin]').val(),
            'filter_age_begin' : $('[filter_age_begin]').val(),
            'filter_operation_end' : $('[filter_operation_end]').val(),
            'filter_age_end' : $('[filter_age_end]').val(),
            'filter_ddd' : $('[filter_ddd]').val(),
            'filter_cep' : $('[filter_cep]').val(),
            'filter_users' : $('[data-filter-users]').val(),              
        }
      },

      onBtnFilterClick: function(event) {

        var val = this.getFilterValues();   
  
        const token = $('meta[name="csrf-token"]').attr('content');
  
        const searching = $.ajax({ 
            method: 'POST',
            url: '/actions/search-filter',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              values: val,
              dataType: 'json',
            }),
        })
    
        searching.done($.proxy(this.onSearchingSuccess, this));
        searching.fail($.proxy(this.onSearchingFail, this)); 
  
      },
  
      onSearchingSuccess: function(data) {
  
        this.$textFilter.html(data);
        this.$usersFilter.val(data);
  
      },
  
      onSearchingFail: function(error) {
        console.log(error);
      },

};

  $(function() {
    ScreenExchangesSm.start();
  })
})(jQuery);